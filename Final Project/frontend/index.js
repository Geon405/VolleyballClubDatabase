document.addEventListener('DOMContentLoaded', () => {

});

document.addEventListener("htmx:afterSwap", function(event) {
    let resultsDiv = document.getElementById("results");
    if (resultsDiv.innerHTML.trim() !== "") {
        resultsDiv.style.display = "block";
    }
    else{
        resultsDiv.style.display = "none";
    }
});

function editRow(button, tableName){
    console.log(`EDIT ${tableName}\n`);
    //gather from document, the current row by which this edit button is associated
    //depending on the table, we only allow certain fields to be editable (e.g. if location, then name, address, etc)
    //note: cannot edit PK or FK

    if (tableName === 'location'){
        const editable_columns = [
            'name',
            'type',
            'address',
            'phone_number', //TODO: maybe we have a pre-existing dropdown list of available postal codes
            'web_address',
            'max_capacity',
        ];
        const row = button.closest('tr');
        const table = row.closest('table');
        const headers = table.querySelectorAll('thead th');
        const cells = row.querySelectorAll('td');

        cells.forEach((cell, index) => {
            const header = headers[index];

            editable_columns.forEach(editable_column => {
                if (header.textContent.trim() === editable_column) {

                    if (!cell.querySelector('input')){
                        const currentContent = cell.textContent.trim();

                        const input = document.createElement('input');
                        input.type = 'text';
                        input.value = currentContent;

                        input.classList.add('inline-edit');

                        cell.innerHTML = '';
                        cell.appendChild(input);

                        //TODO: add the same even listener to each input
                        //to listen for enter key which will trigger an update JS function to crud_endpoint.php
                        //this JS funcion will take in the current row, and remake it with the new info post-commit to db
                        //and reinsert the new row here
                        input.addEventListener('keydown', function(event){
                            if (event.key === 'Enter'){
                                submitRowEdit(row, 'location');
                            }
                        });
                    }

                }
            });

        });

    }

    if(tableName === 'personnel'){
        //TODO: handle personnel actions
    }

    if(tableName === 'family_member'){
        //TODO: handle fam actions
    }

    if(tableName === 'club_member'){
        //TODO: handle club mem actions
    }

    if(tableName === 'team'){
        //TODO: handle team actions
    }


}


function submitRowEdit(row, entityName) {
    const cells = row.querySelectorAll('td');

    const formData = new FormData();
    formData.append('crud_action', 'edit');
    formData.append('target_entity', entityName);

    const headers = row.closest('table').querySelectorAll('thead th');

    cells.forEach((cell, index) => {
        const headerText = headers[index].textContent.trim().toLowerCase().replace(/\s+/g, '_');
        const input = cell.querySelector('input');
        const select = cell.querySelector('select');

        let value = '';
        if (select) {
            value = select.value;
        } else if (input) {
            value = input.value;
        } else {
            value = cell.textContent.trim();
        }

        formData.append(headerText, value);
    });

    const idField = headers[0].textContent.trim().toLowerCase().replace(/\s+/g, '_');
    formData.append(idField, cells[0].textContent.trim());

    fetch('/frontend/crud_endpoint.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            row.outerHTML = data.row;
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error('Update error:', err);
        alert('Something went wrong while updating.');
    });

}



function deleteRow(button, entity) {
    const row = button.closest('tr');
    const pkMap = {
        location: 'location_id',
        personnel: 'personnel_id',
        club_member: 'club_member_id',
        family_member: 'family_member_id',
        team: 'team_id',
        team_member: 'team_id'
    };

    const pkName = pkMap[entity];
    if (!pkName) {
        alert(`No primary key mapping for entity "${entity}"`);
        return;
    }

    const headers = button.closest('table').querySelectorAll('thead th');
    const cells = row.querySelectorAll('td');
    let pkValue = null;

    headers.forEach((header, index) => {
        const key = header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
        if (key === pkName && cells[index]) {
            pkValue = cells[index].textContent.trim();
        }
    });

    if (!pkValue) {
        alert('Primary key value could not be found.');
        return;
    }

    const confirmed = confirm(`Are you sure you want to delete ${entity} with ID ${pkValue}?`);
    if (!confirmed) return;

    const formData = new FormData();
    formData.append('crud_action', 'delete');
    formData.append('target_entity', entity);
    formData.append(pkName, pkValue);

    fetch('/frontend/crud_endpoint.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(json => {
        if (json.success) {
            row.remove();
        } else {
            alert(json.message || "Delete failed.");
        }
    })
    .catch(err => {
        console.error("Delete error:", err);
        alert("An error occurred during deletion.");
    });
}

function createRow(button, flag){
    const table = button.closest('table');
    const tbody = table.querySelector('tbody');
    const headers = table.querySelectorAll('thead th');

    const newRow = document.createElement('tr');
    newRow.classList.add('new-row');

    headers.forEach((header, index) => {
        const key = header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
        const cell = document.createElement('td');

        if (index === 0){
            cell.textContent = `New ${flag.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}`;
        }
        else if (["team", "team_member"].includes(flag) && key === "team_id") {
            const available_teams = document.createElement('select');
            populateSelectFromBackend(available_teams, "team");
            cell.appendChild(available_teams);
        }

        else if (["team", "team_member"].includes(flag) && key === "club_member_id") {
            const available_members = document.createElement('select');
            populateSelectFromBackend(available_members, "club_member");
            cell.appendChild(available_members);
        }

        else if (flag === "team" && key === "location_id") {
            const available_locations = document.createElement('select');
            populateSelectFromBackend(available_locations, "location");
            cell.appendChild(available_locations);
        }

        else if (flag === "team" && key === "captain_id") {
            const available_captains = document.createElement('select');
            populateSelectFromBackend(available_captains, "club_member");
            cell.appendChild(available_captains);
        }
        else if (flag === "team_member" && key === "role") {
            const roles = document.createElement('select');
            ["Captain", "Member", "Vice Captain"].forEach(role => {
                const option = document.createElement('option');
                option.value = role;
                option.textContent = role;
                roles.appendChild(option);
            });
            cell.appendChild(roles);
        }

        else if(["team"].includes(flag) && ["gender"].includes(key)){
            const available_genders = document.createElement('select');

            ["Male", "Female", "Coed"].forEach(gender => {
                const option = document.createElement('option');
                option.value = gender;
                option.textContent = gender;
                available_genders.appendChild(option);
            });

            cell.appendChild(available_genders);
        }
        else {

            if (["location_id", "personnel_id", "family_member_id", "club_member_id"].includes(key)){
                cell.textContent = '[Auto]';
            }
            else{

                const input = document.createElement('input');
                input.type = 'text';
                input.classList.add('inline-edit');

                const requiredFields = {
                    location: ['name', 'type', 'address'],
                    personnel: ['sin'],
                    family_member: ['sin'],
                    club_member: ['sin', 'status'],
                    team: ['name', 'gender'],
                    team_member: ['team_id', 'club_member_id', 'role']
                };

                if (requiredFields[flag]?.includes(key)) {
                    input.placeholder = `${header.textContent.trim()} *`;
                    input.required = true;
                } else {
                    input.placeholder = header.textContent.trim();
                }

                input.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        submitNewRow(newRow, flag);
                    }
                });

                cell.appendChild(input);
            }


        }

        newRow.appendChild(cell);
    });

    tbody.appendChild(newRow);
    newRow.scrollIntoView({ behavior: 'smooth', block: 'end' });
}


function submitNewRow(row, entity) {
    const headers = row.closest('table').querySelectorAll('thead th');
    const cells = row.querySelectorAll('td');
    const formData = new FormData();

    formData.append('crud_action', 'create');
    formData.append('target_entity', entity);

    let valid = true;

    headers.forEach((header, index) => {
        const key = header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
        const cell = cells[index];
        const input = cell.querySelector('input');

        const select = cell.querySelector('select');

        let value = '';
        if (select) {
            value = select.value;
        } else if (input) {
            value = input.value;
        } else {
            value = cell.textContent.trim();
        }

        value = value.trim();

        formData.append(key, value);

        //TODO: Check validity of inputs contingent upon table category
        // if (entity === 'team_member'){
        //     console.log();
        // }
        //   // if (entity === 'location' && ['name', 'type', 'address'].includes(key) && value === '') {
        //     input.classList.add('input-error');
        //     valid = false;
        // } else {
        //     input.classList.remove('input-error');
        // }
    });

    if (!valid) {
        alert("Please fill all required fields (*) before submitting.");
        return;
    }

    fetch('/frontend/crud_endpoint.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(json => {
            if (json.success && json.row) {
                const tempRow = row;
                const newRow = document.createElement('tr');
                newRow.innerHTML = json.row;
                tempRow.replaceWith(newRow);
            } else {
                alert(json.message || "Failed to create record.");
            }
        })
        .catch(err => {
            console.error("Create error:", err);
            alert("An error occurred during creation.");
        });
}


async function populateSelectFromBackend(selectElement, entity) {
    try {
        const res = await fetch(`/frontend/crud_endpoint.php?target_entity=${entity}&crud_action=populate`);
        const data = await res.json();

        if (!Array.isArray(data)) throw new Error("Invalid data format");

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item['value'];
            option.textContent = item['label'];
            selectElement.appendChild(option);
        });
    } catch (err) {
        console.error(`Failed to fetch ${entity} options:`, err);
        const option = document.createElement('option');
        option.textContent = 'Error loading';
        selectElement.appendChild(option);
    }
}

