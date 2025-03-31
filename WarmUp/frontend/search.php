<?php

require_once __DIR__ . './../backend/database.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

switch ($query) {

    //SQL script #1
    case '(i) location_details':
        $sql = "SELECT
                    Locations.*,
                    COUNT(ClubMembers.club_member_id) AS NumberOfMembers,
                    (CASE 
                        WHEN PersonnelSum.personnel_count IS NULL THEN 0
                        ELSE PersonnelSum.personnel_count
                    END) AS NumberOfPersonnel,
                    (CASE
                        WHEN Locations.type = 'Head' THEN 
                        (
                            SELECT CONCAT(Personnel.first_name, ' ', Personnel.last_name)
                            FROM Personnel, OperatesAt, Locations
                            WHERE OperatesAt.location_id = Locations.location_id
                                AND OperatesAt.personnel_id = Personnel.personnel_id 
                                AND Personnel.role = 'General Manager'
                        )
                        ELSE 'N/A'
                    END) AS `GM Name` 
                FROM Locations
                LEFT JOIN ClubMembers ON Locations.location_id = ClubMembers.location_id
                LEFT JOIN (
                    SELECT 
                        location_id, 
                        COUNT(OperatesAt.personnel_id) AS personnel_count
                    FROM OperatesAt
                    WHERE OperatesAt.end_date IS NULL
                    GROUP BY location_id
                ) AS PersonnelSum ON PersonnelSum.location_id = Locations.location_id
                GROUP BY Locations.location_id
                ORDER BY Locations.province ASC, Locations.city ASC;";
        $result = $conn->query($sql);
        break;

    //SQL script #2
    case '(ii) family_members_report':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT
                    Locations.name AS LocationName,
                    FamilyMembers.first_name AS FirstName,
                    FamilyMembers.last_name AS LastName,
                    (CASE
                        WHEN ActiveClubMemberSums.active_count IS NULL THEN 0
                        ELSE ActiveClubMemberSums.active_count
                    END) AS ActiveMembers
                FROM FamilyMembers
                JOIN ClubMembers ON FamilyMembers.family_member_id = ClubMembers.family_member_id
                JOIN Locations ON FamilyMembers.location_id = Locations.location_id
                LEFT JOIN (
                    SELECT 
                        COUNT(ActiveClubMembers.club_member_id) AS active_count,
                        ActiveClubMembers.family_member_id
                    FROM (
                        SELECT
                            ClubMembers.family_member_id,
                            ClubMembers.club_member_id
                        FROM Payments, ClubMembers
                        WHERE Payments.club_member_id = ClubMembers.club_member_id 
                            AND Payments.payment_year = YEAR(CURDATE())
                            AND TIMESTAMPDIFF(YEAR, ClubMembers.date_of_birth, CURDATE()) BETWEEN 11 AND 18
                        GROUP BY ClubMembers.club_member_id
                        HAVING SUM(Payments.amount) >= 100
                    ) AS ActiveClubMembers
                    GROUP BY ActiveClubMembers.family_member_id
                ) AS ActiveClubMemberSums
                    ON ActiveClubMemberSums.family_member_id = FamilyMembers.family_member_id
                WHERE Locations.name LIKE ?
                GROUP BY Locations.name, FamilyMembers.family_member_id
                ORDER BY Locations.name, FamilyMembers.last_name;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    //SQL script #3
    case '(iii) personnel_details':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT Locations.name AS LocationName,
                    Personnel.first_name,
                    Personnel.last_name,
                    Personnel.date_of_birth,
                    Personnel.ssn,
                    Personnel.medicare_number,
                    Personnel.phone_number,
                    Personnel.address,
                    Personnel.city,
                    Personnel.province,
                    Personnel.postal_code,
                    Personnel.email,
                    Personnel.role,
                    Personnel.mandate
                FROM Personnel
                JOIN OperatesAt ON Personnel.personnel_id = OperatesAt.personnel_id
                JOIN Locations ON OperatesAt.location_id = Locations.location_id
                WHERE OperatesAt.end_date IS NULL
                    AND Locations.name LIKE ?
                ORDER BY Locations.name;";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();
        break;

    //SQL script #4
    case '(iv) all_club_members_list':
        $sql = "SELECT
                    Locations.name AS `Location Name`,
                    TIMESTAMPDIFF(YEAR, ClubMembers.date_of_birth, CURDATE()) AS Age,
                    ClubMembers.club_member_id,
                    ClubMembers.first_name,
                    ClubMembers.last_name,
                    ClubMembers.city,
                    ClubMembers.province,
                    (CASE
                        WHEN ActiveClubMembers.club_member_id IS NOT NULL THEN 'Active'
                        ELSE 'Inactive'
                    END) AS Status
                FROM ClubMembers
                JOIN Locations ON ClubMembers.location_id = Locations.location_id
                LEFT JOIN (
                    SELECT
                        ClubMembers.club_member_id
                    FROM Payments, ClubMembers
                    WHERE Payments.club_member_id = ClubMembers.club_member_id 
                        AND Payments.payment_year = YEAR(CURDATE())
                        AND TIMESTAMPDIFF(YEAR, ClubMembers.date_of_birth, CURDATE()) BETWEEN 11 AND 18
                    GROUP BY ClubMembers.club_member_id
                    HAVING SUM(Payments.amount) >= 100
                ) AS ActiveClubMembers
                    ON ActiveClubMembers.club_member_id = ClubMembers.club_member_id
                ORDER BY Locations.name ASC, Age ASC;";
        $result = $conn->query($sql);
        break;

    //SQL script #5
    case '(v) club_members_for_given_family_member':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT
                    FamilyMembers.first_name AS `Relative's First Name`,
                    FamilyMembers.last_name AS `Relative's Last Name`,
                    ClubMembers.club_member_id,
                    ClubMembers.first_name AS `ClubMember's First Name`,
                    ClubMembers.last_name AS `ClubMember's Last Name`,
                    ClubMembers.date_of_birth,
                    ClubMembers.ssn,
                    ClubMembers.medicare_number,
                    ClubMembers.phone_number,
                    ClubMembers.address,
                    ClubMembers.city,
                    ClubMembers.province,
                    ClubMembers.postal_code,
                    ClubMembers.relation AS `Relationship`,
                    (CASE
                        WHEN ActiveClubMembers.club_member_id IS NOT NULL THEN 'Active'
                        ELSE 'Inactive'
                    END) AS Status
                FROM ClubMembers
                JOIN FamilyMembers 
                    ON FamilyMembers.family_member_id = ClubMembers.family_member_id
                LEFT JOIN (
                    SELECT
                        ClubMembers.club_member_id
                    FROM Payments, ClubMembers
                    WHERE Payments.club_member_id = ClubMembers.club_member_id 
                        AND Payments.payment_year = YEAR(CURDATE())
                        AND TIMESTAMPDIFF(YEAR, ClubMembers.date_of_birth, CURDATE()) BETWEEN 11 AND 18
                    GROUP BY ClubMembers.club_member_id
                    HAVING SUM(Payments.amount) >= 100
                ) AS ActiveClubMembers
                    ON ActiveClubMembers.club_member_id = ClubMembers.club_member_id
                WHERE FamilyMembers.first_name LIKE ? OR FamilyMembers.last_name LIKE ?
                ORDER BY FamilyMembers.first_name, FamilyMembers.last_name, ClubMembers.first_name;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    break;

    //SQL script #6
    case '(vi) active_club_members_by_location_list':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT DISTINCT
                    Locations.name AS `Location`,
                    FamilyMembers.first_name AS `First Name`,
                    FamilyMembers.last_name AS `Last Name`,
                    FamilyMembers.phone_number AS `Phone Number`
                FROM FamilyMembers
                JOIN (
                    SELECT
                        ClubMembers.club_member_id,
                        ClubMembers.family_member_id,
                        ClubMembers.location_id
                    FROM Payments, ClubMembers
                    WHERE Payments.club_member_id = ClubMembers.club_member_id 
                        AND Payments.payment_year = YEAR(CURDATE())
                        AND TIMESTAMPDIFF(YEAR, ClubMembers.date_of_birth, CURDATE()) BETWEEN 11 AND 18
                    GROUP BY ClubMembers.club_member_id
                    HAVING SUM(Payments.amount) >= 100
                ) AS ActiveClubMembers
                    ON FamilyMembers.family_member_id = ActiveClubMembers.family_member_id -- matches family member to active club member
                JOIN Personnel
                    ON FamilyMembers.ssn = Personnel.ssn                         -- TODO: This will have to be changed to a better reference.
                JOIN OperatesAt
                    ON Personnel.personnel_id = OperatesAt.personnel_id
                    AND OperatesAt.end_date IS NULL                     -- Currently working at location
                JOIN Locations
                    ON FamilyMembers.location_id = Locations.location_id
                    AND ActiveClubMembers.location_id = Locations.location_id
                    AND OperatesAt.location_id = Locations.location_id  -- All entities must match location
                WHERE Locations.name LIKE ?
                ORDER BY FamilyMembers.first_name, FamilyMembers.last_name;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $result = $stmt->get_result();
    break;

    //SQL script #7
    case '(vii) member_payments':
        $search = ($search === "*") ? "%" : "%{$search}%";
        $sql = "SELECT
                       ClubMembers.first_name,
                       ClubMembers.last_name,
                       payment_date,
                       amount,
                       payment_year
                FROM Payments
                JOIN ClubMembers
                    ON ClubMembers.club_member_id = Payments.club_member_id
                WHERE ClubMembers.first_name LIKE ? OR ClubMembers.last_name LIKE ?
                ORDER BY payment_date ASC;";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    break;

    //SQL script #8
    case '(viii) sum_of_member_payments_and_donations_2024':
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
                            SELECT SUM(Payments.amount) AS payment_sum, ClubMembers.club_member_id, Payments.payment_date
                            FROM Payments, ClubMembers
                            WHERE Payments.club_member_id = ClubMembers.club_member_id
                            AND YEAR(Payments.payment_date) = 2024       -- TODO change this depends whether it should be payment date or payment year
                            GROUP BY ClubMembers.club_member_id) AS ClubMemberPaymentSums";
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
    while ($fieldInfo = $result->fetch_field()) {
        echo "<th>" . htmlspecialchars($fieldInfo->name) . "</th>";
    }
    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    // Print table rows
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
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
