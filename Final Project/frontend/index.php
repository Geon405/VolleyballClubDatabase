<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search MYVC Database</title>
    <link rel="stylesheet" href="https://lqc353.encs.concordia.ca/frontend/global_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="/frontend/htmx.min.js"></script>
    <script src="https://lqc353.encs.concordia.ca/frontend/index.js"></script>
</head>

<body>
    <div id='header-div'>
        <div class="logo-div">
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
                    <option value="(i) crud_location">(i) Create/Edit/Delete/Display a Location</option>
                    <option value="(ii) crud_personnel">(ii) Create/Edit/Delete/Display a Personnel</option>
                    <option value="(iii) crud_family_member">(iii) Create/Edit/Delete/Display a Family Member</option>
                    <option value="(iv) crud_club_member">(iv) Create/Edit/Delete/Display a Club Member</option>
                    <option value="(v) crud_team_formation">(v) Create/Edit/Delete/Display a Team Formation</option>
                    <option value="(vi) crud_team_assignment">(vi) Assign/Edit/Delete a Club Member to Team Formation</option>
                    <option value="(vii) location_details">(vii) Location Details</option>
                    <option value="(viii) family_members_report">(viii) Family Members Report</option>
                    <option value="(ix) weekly_team_formations_for_location">(ix) Weekly Team Formations for Location</option>
                    <option value="(x) active_members_3_locations_max_3_years">(x) Active Members - 3 Locations, ≤3 Years</option>
                    <option value="(xi) session_summary_by_location">(xi) Session Summary by Location and Time Period</option>
                    <option value="(xii) active_members_never_assigned_to_team">(xii) Active Members Never Assigned to Team</option>
                    <option value="(xiii) members_only_outside_hitter">(xiii) Members Only Assigned as Outside Hitter</option>
                    <option value="(xiv) members_played_all_roles">(xiv) Members Who Played All Roles</option>
                    <option value="(xv) family_captains_same_location">(xv) Family Captains in Same Location</option>
                    <option value="(xvi) undefeated_members_report">(xvi) Members Who Never Lost a Game</option>
                    <option value="(xvii) treasurer_history_report">(xvii) Treasurer History Report</option>
                    <option value="(xviii) members_deactivated_by_age">(xviii) Members Deactivated by Age</option>
                    <option value="(xix) show_trigger_code">(xix) Show Trigger(s) Used</option>
                    <option value="(xx) show_constraint_integrity">(xx) Show Constraint Integrity Enforcement</option>
                    <option value="(xxi) show_email_logs">(xxi) Show Email Logs</option>
                </select>
                <input type="text" id="search-input" name="search" placeholder="Enter value here" value="">
                <div id="date-range-container" style="display: none; margin-top: 10px;">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" name="start-date">
                    <label for="end-date" style="margin-left: 2px;">End Date:</label>
                    <input type="date" id="end-date" name="end-date">
                </div>

                <button id='search-btn'><i class='bi-search'>Search</i></button>
            </form>
            <div id="outer-results-container">
                <div id="results">
                    <!-- Query results will be dynamically loaded here -->
                </div>
            </div>
    </div>
</body>
</html>