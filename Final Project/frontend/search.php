<?php

require_once __DIR__ . './../backend/database.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$crud_flags = [
    "location" => false,
    "personnel" => false,
    "family_member" => false,
    "club_member" => false,
    "team_formation" => false,
    "team_assignment" => false,
];


switch ($query) {

    case '(i) crud_location':
        $crud_flags["location"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";
        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM Location
                WHERE name LIKE ? OR address LIKE ? OR postal_code LIKE ? OR phone_number LIKE ?
            ");
            $stmt->bind_param("ssss", $like, $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM Location";
            $result = $conn->query($sql);
        }
        break;

    case '(ii) crud_personnel':
        $crud_flags["personnel"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";

        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM Personnel
                WHERE CAST(personnel_id AS CHAR) LIKE ? OR sin LIKE ?
            ");
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM Personnel";
            $result = $conn->query($sql);
        }
        break;


    case '(iii) crud_family_member':
        $crud_flags["family_member"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";
        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM FamilyMember
                WHERE sin LIKE ? OR secondary_family_member_id LIKE ?
            ");
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM FamilyMember";
            $result = $conn->query($sql);
        }
        break;

    case '(iv) crud_club_member':
        $crud_flags["club_member"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";
        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM ClubMember
                WHERE sin LIKE ? OR status LIKE ? OR last_role LIKE ?
            ");
            $stmt->bind_param("sss", $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM ClubMember";
            $result = $conn->query($sql);
        }
        break;

    case '(v) crud_team_formation':
        $crud_flags["team"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";
        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM Team
                WHERE name LIKE ? OR gender LIKE ?
            ");
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM Team";
            $result = $conn->query($sql);
        }
        break;

    case '(vi) crud_team_assignment':
        $crud_flags["team_member"] = true;
        $search = ($search === "*") ? "%" : "%{$search}%";
        if ($search !== '') {
            $like = "%$search%";
            $stmt = $conn->prepare("
                SELECT * FROM TeamMember
                WHERE team_id LIKE ? OR club_member_id LIKE ? OR role LIKE ?
            ");
            $stmt->bind_param("sss", $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $sql = "SELECT * FROM TeamMember";
            $result = $conn->query($sql);
        }
        break;

    // SQL script #7
    case '(vii) location_details':
        $search = ($search === "*" || $search === "") ? "" : "%{$search}%";

        $sql = "SELECT
                    L.location_id,
                    L.name AS LocationName,
                    L.address,
                    PC.city,
                    PC.province,
                    L.postal_code,
                    L.phone_number,
                    L.web_address,
                    L.type,
                    L.max_capacity,
                    CONCAT(P.first_name, ' ', P.last_name) AS `GM Name`,
                    IFNULL(MemberCount.num_members, 0) AS NumberOfMembers,
                    IFNULL(PersonnelSum.personnel_count, 0) AS NumberOfPersonnel
                FROM Location L
                LEFT JOIN PostalCode PC ON L.postal_code = PC.postal_code
                LEFT JOIN Personnel GM ON L.general_manager_id = GM.personnel_id
                LEFT JOIN Person P ON GM.sin = P.sin
                LEFT JOIN (
                    SELECT current_location_id AS location_id, COUNT(*) AS num_members
                    FROM ClubMember
                    GROUP BY current_location_id
                ) AS MemberCount ON MemberCount.location_id = L.location_id
                LEFT JOIN (
                    SELECT location_id, COUNT(*) AS personnel_count
                    FROM OperatesAt
                    WHERE end_date IS NULL
                    GROUP BY location_id
                ) AS PersonnelSum ON PersonnelSum.location_id = L.location_id";

        if (!empty($search)) {
            $sql .= " WHERE
                        CAST(L.location_id AS CHAR) LIKE ?
                        OR L.name LIKE ?
                        OR L.address LIKE ?
                        OR PC.city LIKE ?
                        OR PC.province LIKE ?
                        OR L.postal_code LIKE ?
                        OR L.phone_number LIKE ?
                        OR L.web_address LIKE ?
                        OR L.type LIKE ?";
        }

        $sql .= " ORDER BY PC.province ASC, PC.city ASC";

        $stmt = $conn->prepare($sql);

        if (!empty($search)) {
            $stmt->bind_param("sssssssss", $search, $search, $search, $search, $search, $search, $search, $search, $search);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #8
    case '(viii) family_members_report':
        $search = ($search === "*") ? "%" : "%{$search}%";

        $sql = "SELECT
            -- Secondary family member details
            SFM.first_name AS SecondaryFirstName,
            SFM.last_name AS SecondaryLastName,
            SFM.phone_number AS SecondaryPhone,

            -- Location of the club member
            L.location_id,
            L.name AS LocationName,

            -- Primary family member details
            P1.first_name AS FamilyFirstName,
            P1.last_name AS FamilyLastName,

            -- Relationship and club member details
            CMF.relationship_type AS Relationship,
            CM.club_member_id AS ClubMemberID,

            -- Club member personal info
            P2.first_name AS ClubMemberFirstName,
            P2.last_name AS ClubMemberLastName,
            P2.birth_date AS DOB,
            P2.sin AS SIN,
            P2.medicare_card_number AS Medicare,
            P2.telephone_number AS Phone,
            P2.address AS Address,

            -- Postal info
            PC.city,
            PC.province,
            P2.postal_code AS PostalCode

        FROM FamilyMember FM

        -- Join primary family member info
        JOIN Person P1 ON FM.sin = P1.sin

        -- Join optional secondary family member info
        LEFT JOIN SecondaryFamilyMember SFM
            ON FM.secondary_family_member_id = SFM.secondary_family_member_id

        -- Get related club member(s)
        JOIN ClubMemberFamily CMF ON FM.family_member_id = CMF.family_member_id
        JOIN ClubMember CM ON CMF.club_member_id = CM.club_member_id

        -- Club member's person info
        JOIN Person P2 ON CM.sin = P2.sin

        -- Club member’s current location and city/province
        LEFT JOIN Location L ON CM.current_location_id = L.location_id
        LEFT JOIN PostalCode PC ON P2.postal_code = PC.postal_code

        -- Name filter (search on primary family member)
        WHERE P1.first_name LIKE ? OR P1.last_name LIKE ? OR SFM.first_name LIKE ? OR SFM.last_name LIKE ?

        -- Neatly sorted
        ORDER BY P1.last_name, P2.last_name;
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #9
    case '(ix) weekly_team_formations_for_location':
        $locationName = isset($_GET['location']) ? $_GET['location'] : '';
        $start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
        $end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

        $sql = "SELECT
            HC.first_name AS CoachFirstName,
            HC.last_name AS CoachLastName,
            S.date AS SessionDate,
            S.start_time,
            S.type AS SessionType,
            L.address AS SessionAddress,
            T.name AS TeamName,
            CASE
                WHEN S.date > CURDATE() THEN NULL
                ELSE S.score_team1
            END AS ScoreTeam1,
            CASE
                WHEN S.date > CURDATE() THEN NULL
                ELSE S.score_team2
            END AS ScoreTeam2,
            P.first_name AS PlayerFirstName,
            P.last_name AS PlayerLastName,
            TM.role AS PlayerRole
        FROM Session S
        JOIN Location L ON S.location_id = L.location_id
        JOIN Team T ON (T.team_id = S.team1_id OR T.team_id = S.team2_id)
        JOIN TeamMember TM ON TM.team_id = T.team_id
        JOIN ClubMember CM ON TM.club_member_id = CM.club_member_id
        JOIN Person P ON CM.sin = P.sin
        LEFT JOIN ClubMember Captain ON T.captain_id = Captain.club_member_id
        LEFT JOIN Person HC ON Captain.sin = HC.sin
        WHERE L.name LIKE ?
        AND S.date BETWEEN ? AND ?
        ORDER BY S.date ASC, S.start_time ASC;";

        $stmt = $conn->prepare($sql);
        $likeLoc = "%$locationName%";
        $stmt->bind_param("sss", $likeLoc, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #10
    case '(x) active_members_3_locations_max_3_years':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                WHERE CM.status = 'Active'
                AND CM.join_date >= CURDATE() - INTERVAL 3 YEAR
                AND (
                    SELECT COUNT(DISTINCT S.location_id)
                    FROM TeamMember TM
                    JOIN Team T ON TM.team_id = T.team_id
                    JOIN Session S ON S.team1_id = T.team_id OR S.team2_id = T.team_id
                    WHERE TM.club_member_id = CM.club_member_id
                ) >= 3
                AND (
                    P.first_name LIKE ? OR
                    P.last_name LIKE ? OR
                    CM.club_member_id LIKE ?
                )
                ORDER BY CM.club_member_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #11
    case '(xi) session_summary_by_location':
        $start_date = isset($_GET['start-date']) ? $_GET['start-date'] : null;
        $end_date = isset($_GET['end-date']) ? $_GET['end-date'] : null;

        $sql = "SELECT
                L.name AS location_name,
                COUNT(CASE WHEN S.type = 'Training' THEN 1 END) AS total_training_sessions,
                SUM(CASE WHEN S.type = 'Training' THEN (
                    IFNULL(TM1.training_players, 0) + IFNULL(TM2.training_players, 0)
                ) ELSE 0 END) AS total_training_players,
                COUNT(CASE WHEN S.type = 'Game' THEN 1 END) AS total_game_sessions,
                SUM(CASE WHEN S.type = 'Game' THEN (
                    IFNULL(TM1.game_players, 0) + IFNULL(TM2.game_players, 0)
                ) ELSE 0 END) AS total_game_players
            FROM Location L
            JOIN Session S ON L.location_id = S.location_id

            -- Team 1 player counts
            LEFT JOIN (
                SELECT team_id,
                    COUNT(club_member_id) AS training_players,
                    COUNT(club_member_id) AS game_players
                FROM TeamMember
                GROUP BY team_id
            ) AS TM1 ON TM1.team_id = S.team1_id

            -- Team 2 player counts
            LEFT JOIN (
                SELECT team_id,
                    COUNT(club_member_id) AS training_players,
                    COUNT(club_member_id) AS game_players
                FROM TeamMember
                GROUP BY team_id
            ) AS TM2 ON TM2.team_id = S.team2_id

            WHERE S.date BETWEEN ? AND ?
            GROUP BY L.location_id
            HAVING total_game_sessions >= 2
            ORDER BY total_game_sessions DESC
            ;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #12
    case '(xii) active_members_never_assigned_to_team':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name,
                    TIMESTAMPDIFF(YEAR, P.birth_date, CURDATE()) AS age,
                    CM.join_date,
                    CM.status,
                    P.telephone_number AS phone_number,
                    P.email_address AS email,
                    L.name AS location_name
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                    AND CM.club_member_id NOT IN (
                        SELECT DISTINCT club_member_id
                        FROM TeamMember
                    )
                    AND (
                        P.first_name LIKE ? OR
                        P.last_name LIKE ? OR
                        P.email_address LIKE ? OR
                        P.telephone_number LIKE ? OR
                        L.name LIKE ?
                    )
                ORDER BY L.name ASC, CM.club_member_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #13
    case '(xiii) members_only_outside_hitter':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name,
                    TIMESTAMPDIFF(YEAR, P.birth_date, CURDATE()) AS age,
                    P.telephone_number,
                    P.email_address,
                    L.name AS location_name
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                  AND CM.club_member_id IN (
                      SELECT TM.club_member_id
                      FROM TeamMember TM
                      WHERE TM.role = 'Outside Hitter'
                  )
                  AND CM.club_member_id NOT IN (
                      SELECT TM.club_member_id
                      FROM TeamMember TM
                      WHERE TM.role != 'Outside Hitter'
                  )
                  AND (
                      P.first_name LIKE ? OR
                      P.last_name LIKE ? OR
                      P.email_address LIKE ? OR
                      P.telephone_number LIKE ? OR
                      L.name LIKE ?
                  )
                ORDER BY location_name ASC, CM.club_member_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #14 - Active members with all 7 roles in game sessions
    case '(xiv) members_played_all_roles':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name,
                    TIMESTAMPDIFF(YEAR, P.birth_date, CURDATE()) AS age,
                    P.telephone_number AS phone_number,
                    P.email_address AS email,
                    L.name AS current_location_name
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                AND CM.club_member_id IN (
                    SELECT TM.club_member_id
                    FROM TeamMember TM
                    JOIN Session S
                    ON TM.team_id = S.team1_id OR TM.team_id = S.team2_id
                    WHERE S.type = 'Game'
                    GROUP BY TM.club_member_id
                    HAVING
                        SUM(TM.role = 'Outside Hitter') > 0 AND
                        SUM(TM.role = 'Opposite') > 0 AND
                        SUM(TM.role = 'Setter') > 0 AND
                        SUM(TM.role = 'Middle Blocker') > 0 AND
                        SUM(TM.role = 'Libero') > 0 AND
                        SUM(TM.role = 'Defensive Specialist') > 0 AND
                        SUM(TM.role = 'Serving Specialist') > 0
                )
                AND (
                    P.first_name LIKE ? OR
                    P.last_name LIKE ? OR
                    P.email_address LIKE ? OR
                    L.name LIKE ?
                )
                ORDER BY L.name ASC, CM.club_member_id ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #15
    case '(xv) family_captains_same_location':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    P1.first_name,
                    P1.last_name,
                    P1.telephone_number,
                    L.name AS location_name
                FROM FamilyMember FM
                JOIN ClubMemberFamily CMF ON FM.family_member_id = CMF.family_member_id
                JOIN ClubMember CM ON CMF.club_member_id = CM.club_member_id
                JOIN Team T ON T.captain_id = CM.club_member_id
                JOIN Location L ON T.location_id = L.location_id
                JOIN Person P1 ON FM.sin = P1.sin
                WHERE CM.status = 'Active'
                  AND L.name LIKE ?;";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die(" SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #16
    case '(xvi) undefeated_members_report':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT DISTINCT
                    ActiveClubMember.club_member_id,
                    first_name,
                    last_name,
                    timestampdiff(YEAR, birth_date, CURDATE()) AS age,
                    telephone_number,
                    Location.name AS location_name
                FROM ActiveClubMember
                INNER JOIN ClubMemberInformation ON ActiveClubMember.club_member_id = ClubMemberInformation.club_member_id
                INNER JOIN TeamMember ON ActiveClubMember.club_member_id = TeamMember.club_member_id
                INNER JOIN Location ON Location.location_id = ClubMemberInformation.current_location_id
                WHERE
                    ClubMemberInformation.club_member_id NOT IN (SELECT club_member_id FROM LosingTeamMember)
                    AND ClubMemberInformation.club_member_id IN (SELECT club_member_id FROM GameParticipatedTeamMember)
                    AND (
                        first_name LIKE ? OR
                        last_name LIKE ? OR
                        telephone_number LIKE ? OR
                        Location.name LIKE ?
                    )
                ORDER BY Location.name, club_member_id;";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ssss", $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #17
    case '(xvii) treasurer_history_report':
        $sql = "SELECT
    p.first_name,
    p.last_name,
    o.start_date,
    COALESCE(CAST(o.end_date AS CHAR), '-') AS end_date,
    CASE
        WHEN o.end_date IS NULL THEN 'Current Treasurer'
        ELSE 'Former Treasurer'
    END AS status
FROM
    Person p
JOIN
    Personnel pl ON p.sin = pl.sin
JOIN
    OperatesAt o ON pl.personnel_id = o.personnel_id
WHERE
    o.role = 'Treasurer'
ORDER BY
    p.first_name ASC,
    p.last_name ASC,
    o.start_date ASC;";
        $result = $conn->query($sql);

        // $sql = "SELECT
        //             first_name,
        //             last_name,
        //             start_date,
        //             COALESCE(end_date, '-') AS end_date
        //         FROM personnelinformation
        //         WHERE role = 'Treasurer'
        //         ORDER BY first_name, last_name, start_date;";
        // $result = $conn->query($sql);
        break;



    // SQL script #18
    case '(xviii) members_deactivated_by_age':
        $search = ($search === "*" || $search === "") ? "%" : "%{$search}%";

        $sql = "SELECT
                    P.first_name,
                    P.last_name,
                    P.telephone_number,
                    P.email_address,
                    CM.deactivation_date,
                    L.name AS last_location_name,
                    CM.last_role
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                JOIN Location L ON CM.last_location_id = L.location_id
                WHERE CM.status = 'Inactive'
                AND TIMESTAMPDIFF(YEAR, P.birth_date, CM.deactivation_date) > 18
                AND (
                    P.first_name LIKE ? OR
                    P.last_name LIKE ? OR
                    P.email_address LIKE ? OR
                    L.name LIKE ? OR
                    CM.last_role LIKE ?
                )
                ORDER BY L.name ASC, CM.last_role ASC, P.first_name ASC, P.last_name ASC;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    // SQL script #19
    case '(xix) outside_hitters_only':
        $sql = "SELECT
                    CM.club_member_id,
                    CM.first_name,
                    CM.last_name,
                    TIMESTAMPDIFF(YEAR, CM.birth_date, CURDATE()) AS Age,
                    CM.telephone_number,
                    CM.email_address,
                    L.name AS CurrentLocation
                FROM ClubMember CM
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                AND CM.club_member_id IN (
                    SELECT TM.club_member_id
                    FROM TeamMember TM
                    GROUP BY TM.club_member_id
                    HAVING SUM(CASE WHEN TM.role != 'Outside Hitter' THEN 1 ELSE 0 END) = 0
                        AND SUM(CASE WHEN TM.role = 'Outside Hitter' THEN 1 ELSE 0 END) > 0
                )
                ORDER BY L.name ASC, CM.club_member_id ASC";
        $result = $conn->query($sql);
        break;

    // SQL script #20
    case '(xx) all_roles_in_games':
        $sql = "SELECT
                    CM.club_member_id,
                    CM.first_name,
                    CM.last_name,
                    TIMESTAMPDIFF(YEAR, CM.birth_date, CURDATE()) AS Age,
                    CM.telephone_number,
                    CM.email_address,
                    L.name AS CurrentLocation
                FROM ClubMember CM
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                AND CM.club_member_id IN (
                    SELECT TM.club_member_id
                    FROM TeamMember TM
                    JOIN Session S ON TM.team_id = S.team1_id OR TM.team_id = S.team2_id
                    WHERE S.type = 'Game'
                    GROUP BY TM.club_member_id
                    HAVING
                        SUM(TM.role = 'Outside Hitter') > 0 AND
                        SUM(TM.role = 'Opposite') > 0 AND
                        SUM(TM.role = 'Setter') > 0 AND
                        SUM(TM.role = 'Middle Blocker') > 0 AND
                        SUM(TM.role = 'Libero') > 0 AND
                        SUM(TM.role = 'Defensive Specialist') > 0 AND
                        SUM(TM.role = 'Serving Specialist') > 0
                )
                ORDER BY L.name ASC, CM.club_member_id ASC";
        $result = $conn->query($sql);
        break;

    // SQL script #21
    case '(xxi) show_email_logs':
        $sql = "SELECT
                    email_date,
                    sender,
                    recipient_email,
                    subject,
                    mail_body,
                    email_type
                FROM EmailLog";
        $result = $conn->query($sql);
        break;


    default:
        echo "<p class='no-results-p'>Invalid query. Please Select an Option</p>";
        exit();
}

if ($result && $result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<table>";
    echo "<thead>";
    echo "<tr>";

    foreach ($crud_flags as $flag => $value){
        if ($value === true){
            echo "<th><button data-entity='$flag' class=\"create-btn\" onclick=\"createOrEditRow(this, '$flag')\"><i class=\"bi bi-plus-circle\">CREATE</i></button></th>";
            break;
        }
    }

    while ($fieldInfo = $result->fetch_field()) {
        echo "<th>" . htmlspecialchars($fieldInfo->name) . "</th>";
    }
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($crud_flags as $flag => $value){
            if ($value === true){
                echo "<td>
                <button class=\"action-btn edit-btn\" onclick=\"createOrEditRow(this, '$flag', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
                <button class=\"action-btn delete-btn\" onclick=\"deleteRow(this, '$flag')\"><i class=\"bi bi-trash\">DEL</i></button>
                </td>";
                break;
            }
        }

        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<p class='no-results-p'>No results found for the selected query.</p>";
}

$conn->close();
?>
