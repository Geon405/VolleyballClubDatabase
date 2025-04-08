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

    case '(vii) location_details':

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
                ) AS PersonnelSum ON PersonnelSum.location_id = L.location_id
                ORDER BY PC.province ASC, PC.city ASC";

        $result = $conn->query($sql);
        break;



    case '(viii) family_members_report':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT
                    L.name AS LocationName,
                    P1.first_name AS FamilyFirstName,
                    P1.last_name AS FamilyLastName,
                    SFM.first_name AS SecondaryFirstName,
                    SFM.last_name AS SecondaryLastName,
                    SFM.phone_number AS SecondaryPhone,
                    CMF.relationship_type AS Relationship,
                    CM.club_member_id AS ClubMemberID,
                    P2.first_name AS ClubMemberFirstName,
                    P2.last_name AS ClubMemberLastName,
                    P2.birth_date AS DOB,
                    P2.sin AS SIN,
                    P2.medicare_card_number AS Medicare,
                    P2.telephone_number AS Phone,
                    P2.address AS Address,
                    PC.city,
                    PC.province,
                    P2.postal_code AS PostalCode
                FROM FamilyMember FM
                JOIN Person P1 ON FM.sin = P1.sin
                LEFT JOIN SecondaryFamilyMember SFM ON FM.secondary_family_member_id = SFM.secondary_family_member_id
                JOIN ClubMemberFamily CMF ON FM.family_member_id = CMF.family_member_id
                JOIN ClubMember CM ON CMF.club_member_id = CM.club_member_id
                JOIN Person P2 ON CM.sin = P2.sin
                LEFT JOIN Location L ON CM.current_location_id = L.location_id
                LEFT JOIN PostalCode PC ON P2.postal_code = PC.postal_code
                WHERE P1.first_name LIKE ? OR P1.last_name LIKE ?
                ORDER BY P1.last_name, P2.last_name;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    case '(ix) team_rosters':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $start_date = date('Y-m-d', strtotime('monday this week')); // or from user input
        $end_date = date('Y-m-d', strtotime('sunday this week'));

        $sql = "SELECT
                    L.name AS LocationName,
                    S.date AS SessionDate,
                    S.start_time AS StartTime,
                    S.type AS SessionType,
                    T.name AS TeamName,
                    S.score_team1,
                    S.score_team2,
                    CONCAT(P.first_name, ' ', P.last_name) AS PlayerName,
                    TM.role AS PlayerRole,
                    HC.first_name AS CoachFirstName,
                    HC.last_name AS CoachLastName
                FROM Session S
                JOIN Location L ON S.location_id = L.location_id
                JOIN Team T ON T.team_id = S.team1_id OR T.team_id = S.team2_id
                JOIN TeamMember TM ON TM.team_id = T.team_id
                JOIN ClubMember CM ON CM.club_member_id = TM.club_member_id
                JOIN Person P ON P.sin = CM.sin
                LEFT JOIN ClubMember Captain ON T.captain_id = Captain.club_member_id
                LEFT JOIN Person HC ON Captain.sin = HC.sin
                WHERE L.name LIKE ?
                    AND S.date BETWEEN ? AND ?
                ORDER BY S.date ASC, S.start_time ASC;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $search, $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


    case '(x) active_club_members_recent_and_multiple_locations':
        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name
                FROM ClubMember CM
                JOIN Person P ON P.sin = CM.sin
                WHERE CM.status = 'Active'
                    AND TIMESTAMPDIFF(YEAR, CM.deactivation_date IS NULL
                                            THEN CURDATE()
                                            ELSE CM.deactivation_date END,
                                    (SELECT MIN(OA.start_date)
                                        FROM OperatesAt OA
                                        JOIN Personnel PER ON PER.personnel_id = OA.personnel_id
                                        WHERE PER.sin = CM.sin)) <= 3
                    AND (
                    SELECT COUNT(DISTINCT OA.location_id)
                    FROM OperatesAt OA
                    JOIN Personnel PER ON OA.personnel_id = PER.personnel_id
                    WHERE PER.sin = CM.sin
                    ) >= 3
                ORDER BY CM.club_member_id ASC;";
        $result = $conn->query($sql);
        break;


    case '(xi) location_formation_summary':
        $search = explode(",", $search); // expecting "2025-01-01,2025-03-31"
        $start_date = trim($search[0]);
        $end_date = trim($search[1]);

        $sql = "SELECT
                    L.name AS location_name,
                    COUNT(CASE WHEN S.type = 'Training' THEN 1 END) AS total_training_sessions,
                    SUM(CASE WHEN S.type = 'Training' THEN TM_count.count ELSE 0 END) AS total_training_players,
                    COUNT(CASE WHEN S.type = 'Game' THEN 1 END) AS total_game_sessions,
                    SUM(CASE WHEN S.type = 'Game' THEN TM_count.count ELSE 0 END) AS total_game_players
                FROM Location L
                JOIN Session S ON L.location_id = S.location_id
                LEFT JOIN (
                    SELECT team_id, COUNT(club_member_id) AS count
                    FROM TeamMember
                    GROUP BY team_id
                ) AS TM_count ON TM_count.team_id IN (S.team1_id, S.team2_id)
                WHERE S.date BETWEEN ? AND ?
                GROUP BY L.location_id
                HAVING total_game_sessions >= 2
                ORDER BY total_game_sessions DESC;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        break;


        case '(xii) unassigned_active_members':
            $sql = "SELECT
                        CM.club_member_id,
                        P.first_name,
                        P.last_name,
                        TIMESTAMPDIFF(YEAR, P.birth_date, CURDATE()) AS age,
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
                    ORDER BY L.name ASC, CM.club_member_id ASC;";

            $result = $conn->query($sql);
            break;


    case '(xiii) only_outside_hitters':
        $sql = "SELECT
                    CM.club_member_id,
                    P.first_name,
                    P.last_name,
                    TIMESTAMPDIFF(YEAR, P.birth_date, CURDATE()) AS age,
                    P.telephone_number AS phone_number,
                    P.email_address AS email,
                    L.name AS location_name
                FROM ClubMember CM
                JOIN Person P ON CM.sin = P.sin
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                    AND CM.club_member_id IN (
                        SELECT club_member_id
                        FROM TeamMember
                        GROUP BY club_member_id
                        HAVING SUM(role != 'Outside Hitter') = 0
                    )
                    AND CM.club_member_id IN (
                        SELECT club_member_id
                        FROM TeamMember
                        WHERE role = 'Outside Hitter'
                    )
                ORDER BY L.name ASC, CM.club_member_id ASC;";

        $result = $conn->query($sql);
        break;


    // SQL script #14
    case '(xiv) sum_of_member_payments_and_donations_2024':
        $sql = "SELECT
                    SUM(CASE
                            WHEN ClubMemberPaymentSums.payment_sum >= 100 THEN 100
                            ELSE ClubMemberPaymentSums.payment_sum
                        END) AS `Total Membership Fees in 2024`,
                    SUM(CASE
                            WHEN ClubMemberPaymentSums.payment_sum - 100 <= 0 THEN 0
                            ELSE ClubMemberPaymentSums.payment_sum - 100
                        END) AS `Total Donations in 2024`
                FROM (
                    SELECT
                        SUM(Payments.amount) AS payment_sum,
                        ClubMembers.club_member_id
                    FROM Payments
                    JOIN ClubMembers ON Payments.club_member_id = ClubMembers.club_member_id
                    WHERE Payments.payment_year = 2024
                    GROUP BY ClubMembers.club_member_id
                ) AS ClubMemberPaymentSums";
        $result = $conn->query($sql);
        break;

    // SQL script #15
    case '(xv) team_formations_by_week':
        $search = ($search === "*") ? "%" : "%{$search}%"; // This is used for location name
        $sql = "SELECT
                    T.name AS TeamName,
                    S.date AS SessionDate,
                    S.start_time AS StartTime,
                    S.type AS SessionType,
                    L.address AS SessionAddress,
                    COALESCE(S.score_team1, '-') AS ScoreTeam1,
                    COALESCE(S.score_team2, '-') AS ScoreTeam2,
                    P.first_name AS CoachFirstName,
                    P.last_name AS CoachLastName,
                    CM.first_name AS PlayerFirstName,
                    CM.last_name AS PlayerLastName,
                    TM.role AS PlayerRole
                FROM Session S
                JOIN Location L ON S.location_id = L.location_id
                JOIN Team T ON S.team1_id = T.team_id
                JOIN TeamMember TM ON TM.team_id = T.team_id
                JOIN ClubMember CM ON TM.club_member_id = CM.club_member_id
                LEFT JOIN Personnel P ON T.captain_id = P.personnel_id
                WHERE L.name LIKE ?
                AND WEEK(S.date) = WEEK(CURDATE())
                ORDER BY S.date ASC, S.start_time ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #16
    case '(xvi) active_club_members_min_3_locations':
        $sql = "SELECT
                    CM.club_member_id,
                    CM.first_name,
                    CM.last_name
                FROM ClubMember CM
                JOIN Payments P ON CM.club_member_id = P.club_member_id
                JOIN OperatesAt OA ON OA.personnel_id = CM.club_member_id
                WHERE CM.status = 'Active'
                AND TIMESTAMPDIFF(YEAR, CM.join_date, CURDATE()) <= 3
                GROUP BY CM.club_member_id
                HAVING COUNT(DISTINCT CM.current_location_id) >= 3
                ORDER BY CM.club_member_id ASC";
        $result = $conn->query($sql);
        break;

    // SQL script #17
    case '(xvii) session_summary_by_date_range':
        // Expecting $search to be in format: "2025-01-01,2025-03-31"
        list($startDate, $endDate) = explode(',', $search);

        $sql = "SELECT
                    L.name AS LocationName,
                    SUM(CASE WHEN S.type = 'Training' THEN 1 ELSE 0 END) AS TrainingSessions,
                    SUM(CASE WHEN S.type = 'Training' THEN TM_Train.count ELSE 0 END) AS TrainingPlayers,
                    SUM(CASE WHEN S.type = 'Game' THEN 1 ELSE 0 END) AS GameSessions,
                    SUM(CASE WHEN S.type = 'Game' THEN TM_Game.count ELSE 0 END) AS GamePlayers
                FROM Location L
                JOIN Session S ON L.location_id = S.location_id
                LEFT JOIN (
                    SELECT team_id, COUNT(*) AS count
                    FROM TeamMember
                    GROUP BY team_id
                ) AS TM_Train ON S.team1_id = TM_Train.team_id AND S.type = 'Training'
                LEFT JOIN (
                    SELECT team_id, COUNT(*) AS count
                    FROM TeamMember
                    GROUP BY team_id
                ) AS TM_Game ON S.team1_id = TM_Game.team_id AND S.type = 'Game'
                WHERE S.date BETWEEN ? AND ?
                GROUP BY L.name
                HAVING GameSessions >= 2
                ORDER BY GameSessions DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    // SQL script #18
    case '(xviii) active_members_without_team':
        $sql = "SELECT
                    CM.club_member_id,
                    CM.first_name,
                    CM.last_name,
                    TIMESTAMPDIFF(YEAR, CM.birth_date, CURDATE()) AS Age,
                    CM.join_date,
                    CM.telephone_number,
                    CM.email_address,
                    L.name AS CurrentLocation
                FROM ClubMember CM
                JOIN Location L ON CM.current_location_id = L.location_id
                WHERE CM.status = 'Active'
                AND CM.club_member_id NOT IN (
                    SELECT DISTINCT club_member_id FROM TeamMember
                )
                ORDER BY L.name ASC, CM.club_member_id ASC";
        $result = $conn->query($sql);
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
    case '(xxi) undefeated_club_members':
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
                    HAVING SUM(
                        (TM.team_id = S.team1_id AND S.score_team1 < S.score_team2) OR
                        (TM.team_id = S.team2_id AND S.score_team2 < S.score_team1)
                    ) = 0
                )
                ORDER BY L.name ASC, CM.club_member_id ASC";
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
            echo "<th><button class=\"create-btn\" onclick=\"createRow(this, '$flag')\"><i class=\"bi bi-plus-circle\">CREATE</i></button></th>";
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
                <button class=\"action-btn edit-btn\" onclick=\"editRow(this, '$flag')\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
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
