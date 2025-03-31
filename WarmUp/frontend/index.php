<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search MYVC Database</title>
    <link rel="stylesheet" href="https://lqc353.encs.concordia.ca/frontend/global_style.css">
    <script src="https://unpkg.com/htmx.org"></script>
</head>
<script>
document.addEventListener("htmx:afterSwap", function(event) {
    let resultsDiv = document.getElementById("results");

    if (resultsDiv.innerHTML.trim() !== "") {
        resultsDiv.style.display = "block";
    } else {
        resultsDiv.style.display = "none";
    }
});
</script>
<body>
    <div id='header-div'>
        <div class="logo-div">
            <pre>
        __  ____   ____     ______
        |  \/  \ \ / /\ \   / / ___|
        | |\/| |\ V /  \ \ / / |
        | |  | | | |    \ V /| |___
        |_|  |_| |_|     \_/  \____|
        </pre>
            <h1 id='main-title'>Montreal Youth Volleyball Club Database</h1>
        </div>
            <form hx-get="frontend/search.php"
                  hx-target="#results"
                  hx-swap="innerHTML"
                  hx-include="[name=query], [name=search]"
                  hx-trigger="keydown[key=='Enter'] from:#search-input, click from:#search-btn">
                <label class="choose-query-label" for="query">Choose a query:</label>
                <select name="query" id="query" value="">
                    <option value="">Select a query</option>
                    <option value="(i) location_details">(i) Location Details</option>
                    <option value="(ii) family_members_report">(ii) Family Members Report</option>
                    <option value="(iii) personnel_details">(iii) Personnel Details</option>
                    <option value="(iv) all_club_members_list">(iv) All Club Members List</option>
                    <option value="(v) club_members_for_given_family_member">(v) Club Members Per Family Member List</option>
                    <option value="(vi) active_club_members_by_location_list">(vi) Active Club Members By Location List</option>
                    <option value="(vii) member_payments">(vii) Member Payments</option>
                    <option value="(viii) sum_of_member_payments_and_donations_2024">(viii) Sum Member Payments & Donations in 2024</option>
                </select>
                <input type="text" id="search-input" name="search" placeholder="Enter value here" value="">
                <button id='search-btn'>Search</button>
            </form>
            <div id="outer-results-container">
                <div id="results">
                    <!-- Query results will be dynamically loaded here -->
                </div>
            </div>
    </div>
</body>
</html>