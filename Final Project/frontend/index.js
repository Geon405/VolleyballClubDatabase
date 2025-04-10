document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('query').addEventListener('change', function () {
        const query = this.value;
        const dateContainer = document.getElementById('date-range-container');

        if (query === '(xi) session_summary_by_location' || query === '(ix) weekly_team_formations_for_location') {
            dateContainer.style.display = 'block';
        } else {
            dateContainer.style.display = 'none';
        }
    });

    document.getElementById('search-btn').addEventListener('click', function (e) {
        const query = document.getElementById('query').value;

        if (query === '(xi) session_summary_by_location') {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const searchInput = document.getElementById('search-input');

            // Format as: "YYYY-MM-DD,YYYY-MM-DD"
            searchInput.value = `${startDate},${endDate}`;
        }

        if (query === '(ix) weekly_team_formations_for_location'){
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const searchInput = document.getElementById('search-input');

            // Format as: "YYYY-MM-DD,YYYY-MM-DD"
            searchInput.value = `${startDate},${endDate}`;
        }

    });

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


function deleteRow(button, entity) {
    const row = button.closest('tr');
    const formData = new FormData();
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
        if (key === "club_member_id" && pkName === "team_id"){
            formData.append("club_member_id", cells[index].textContent);
        }
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

function createOrEditRow(button = null, flag, isEdit = false) {
    const table = button.closest('table');
    const tbody = table.querySelector('tbody');
    const headers = table.querySelectorAll('thead th');
    const targetRow = button.closest('tr');
    const row = isEdit ? targetRow : document.createElement('tr');

    //Check if it's been pressed already
    const all_btns = row.querySelectorAll('button');
    let already_pressed = false;

    all_btns.forEach(btn => {
        if (btn.textContent.includes("Submit")) {
            already_pressed = true;
        }
    });

    if (already_pressed) {
        return;
    }

    if (!isEdit) row.classList.add('new-row');

    headers.forEach((header, index) => {
        const key = header.textContent.trim().toLowerCase().replace(/\s+/g, '_');
        const cell = isEdit ? row.children[index] : document.createElement('td');
        const value = isEdit ? cell.textContent.trim() : '';

        if (index === 0) {
            const all_btns = cell.querySelectorAll('button');
            let exists = false;

            all_btns.forEach(btn => {
                if (btn.textContent.includes("Submit")) {
                    exists = true;
                }
            });

            if (exists) {
                return;
            }

            const submitBtn = document.createElement('button');
            submitBtn.innerHTML = `<span><i class="bi bi-check-circle-fill"></i> Submit</span>`;
            submitBtn.addEventListener('click', function () {
                submitNewRow(row, flag, isEdit);
            });
            cell.append(submitBtn);
        } else if (flag === "location" && key === "type") {
            const select = createSelect(['Head', 'Branch'], value);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "location" && key === "general_manager_id") {
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'personnel', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "club_member" && ["current_location_id", "last_location_id"].includes(key)) {
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'location', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "club_member" && key === "status") {
            const select = createSelect(['Active', 'Inactive'], value);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "club_member" && key === "deactivation_date") {
            const input = document.createElement('input');
            input.type = 'date';
            input.classList.add('inline-edit');
            if (isEdit) input.value = value;
            replaceCellContent(cell, input, isEdit);
        } else if (flag === "team" && key === "location_id") {
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'location', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "team" && key === "captain_id") {
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'club_member', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        } else if (flag === "team" && key === "gender") {
            const select = createSelect(['Male', 'Female', 'Coed'], value);
            replaceCellContent(cell, select, isEdit);

        }
        else if(flag === "team" && key === "team_id"){

        }
        else if(flag === "team_member" && key === "team_id"){
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'team', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        }
        else if (["team", "team_member"].includes(flag) && key === "club_member_id") {
            const select = document.createElement('select');
            populateSelectFromBackend(select, 'club_member', isEdit ? value : null);
            replaceCellContent(cell, select, isEdit);
        } else if (["team_member", "club_member"].includes(flag) && ["role", "last_role"].includes(key)) {
            const select = createSelect(["Setter", "Outside Hitter", "Middle Blocker", "Libero", "Defensive Specialist", "Opposite"], value);
            replaceCellContent(cell, select, isEdit);
        } else if (["location_id", "personnel_id", "family_member_id", "club_member_id"].includes(key)) {
            if (!isEdit) cell.textContent = '[Auto]';
        } else {
            const input = document.createElement('input');
            input.type = 'text';
            input.classList.add('inline-edit');
            input.placeholder = header.textContent.trim();
            if (isEdit) input.value = value;
            replaceCellContent(cell, input, isEdit);
        }

        if (!isEdit) row.appendChild(cell);
    });

    if (!isEdit) {
        tbody.appendChild(row);
        row.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
}

function replaceCellContent(cell, element, isEdit) {
    cell.innerHTML = '';
    cell.appendChild(element);

    element.classList.add('inline-edit');
    setTimeout(() => element.focus(), 0);

    element.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            const row = cell.closest('tr');
            const entity = document.querySelector('.create-btn')?.dataset.entity;
            submitNewRow(row, entity, isEdit);
        }
    });
}


function createSelect(optionsArray, selectedValue = '') {
    const select = document.createElement('select');
    optionsArray.forEach(val => {
        const opt = document.createElement('option');
        opt.value = val;
        opt.textContent = val;
        if (val === selectedValue) opt.selected = true;
        select.appendChild(opt);
    });
    return select;
}


function submitNewRow(row, entity, isEdit = false) {
    const headers = row.closest('table').querySelectorAll('thead th');
    const cells = row.querySelectorAll('td');
    const formData = new FormData();

    if (!isEdit){
        formData.append('crud_action', 'create');
    }
    else{
        formData.append('crud_action', 'edit');
    }

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


async function populateSelectFromBackend(selectElement, entity, selectedValue = null) {
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

        if (selectedValue !== null) {
            selectElement.value = selectedValue;
        }

    } catch (err) {
        console.error(`Failed to fetch ${entity} options:`, err);
        const option = document.createElement('option');
        option.textContent = 'Error loading';
        selectElement.appendChild(option);
    }
}

