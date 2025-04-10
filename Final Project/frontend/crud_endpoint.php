<?php

require_once __DIR__ . './../backend/database.php';

$action = isset($_POST['crud_action']) ? $_POST['crud_action'] : (isset($_GET['crud_action']) ? $_GET['crud_action'] : '');
$entity = isset($_POST['target_entity']) ? $_POST['target_entity'] : (isset($_GET['target_entity']) ? $_GET['target_entity'] : '');

switch ($action) {
    case 'create':

        if ($entity === 'location') {
            header('Content-Type: application/json');

            $name = isset($_POST['name']) && $_POST['name'] !== '' ? $_POST['name'] : null;
            $type = isset($_POST['type']) && $_POST['type'] !== '' ? $_POST['type'] : null;
            $address = isset($_POST['address']) && $_POST['address'] !== '' ? $_POST['address'] : null;
            $postal_code = isset($_POST['postal_code']) && $_POST['postal_code'] !== '' ? $_POST['postal_code'] : null;
            $phone_number = isset($_POST['phone_number']) && $_POST['phone_number'] !== '' ? $_POST['phone_number'] : null;
            $web_address = isset($_POST['web_address']) && $_POST['web_address'] !== '' ? $_POST['web_address'] : null;
            $max_capacity = isset($_POST['max_capacity']) && $_POST['max_capacity'] !== '' ? (int)$_POST['max_capacity'] : null;
            $general_manager_id = isset($_POST['general_manager_id']) && $_POST['general_manager_id'] !== '' ? (int)$_POST['general_manager_id'] : null;

            if ($name === null || $type === null || $address === null) {
                echo json_encode([
                    'success' => false,
                    'message' => "Missing required field(s): Name, Type, and Address are required."
                ]);
                break;
            }

            if ($postal_code !== null) {
                $checkPostal = $conn->prepare("SELECT 1 FROM PostalCode WHERE postal_code = ?");
                $checkPostal->bind_param("s", $postal_code);
                $checkPostal->execute();
                $checkPostal->store_result();

                if ($checkPostal->num_rows === 0) {
                    $insertPostal = $conn->prepare("INSERT INTO PostalCode (postal_code) VALUES (?)");
                    if (!$insertPostal->bind_param("s", $postal_code) || !$insertPostal->execute()) {
                        echo json_encode([
                            'success' => false,
                            'message' => "Failed to auto-insert Postal Code '$postal_code'."
                        ]);
                        break;
                    }
                }
            }

            if ($general_manager_id !== null) {
                $checkManager = $conn->prepare("SELECT 1 FROM Personnel WHERE personnel_id = ?");
                $checkManager->bind_param("i", $general_manager_id);
                $checkManager->execute();
                $checkManager->store_result();
                if ($checkManager->num_rows === 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => "General manager ID '$general_manager_id' not found."
                    ]);
                    break;
                }
            }

            $stmt = $conn->prepare("INSERT INTO Location (name, type, address, postal_code, phone_number, web_address, max_capacity, general_manager_id)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssii", $name, $type, $address, $postal_code, $phone_number, $web_address, $max_capacity, $general_manager_id);

            if ($stmt->execute()) {
                $location_id = $conn->insert_id;

                ob_start();
                displayLocationRowOnly($conn, $location_id);
                $rowHTML = ob_get_clean();

                echo json_encode([
                    'success' => true,
                    'row' => $rowHTML
                ]);

                exit;

            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Insert failed: " . htmlspecialchars($stmt->error)
                ]);
            }
        }

        if ($entity === 'personnel') {
            header('Content-Type: application/json');

            $sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;

            if ($sin === null) {
                echo json_encode(['success' => false, 'message' => 'SIN is required.']);
                break;
            }

            $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
            $checkPerson->bind_param("s", $sin);
            $checkPerson->execute();
            $checkPerson->store_result();

            if ($checkPerson->num_rows === 0) {
                $insertPerson = $conn->prepare("INSERT INTO Person (sin) VALUES (?)");
                if (!$insertPerson->bind_param("s", $sin) || !$insertPerson->execute()) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Failed to insert new Person with SIN '$sin'."
                    ]);
                    break;
                }
            }

            $stmt = $conn->prepare("INSERT INTO Personnel (sin) VALUES (?)");
            if (!$stmt->bind_param("s", $sin) || !$stmt->execute()) {
                echo json_encode([
                    'success' => false,
                    'message' => "Failed to insert into Personnel: " . htmlspecialchars($stmt->error)
                ]);
                break;
            }

            $personnel_id = $conn->insert_id;

            ob_start();
            displayPersonnelRowOnly($conn, $personnel_id);
            $rowHTML = ob_get_clean();

            echo json_encode(['success' => true, 'row' => $rowHTML]);
        }


        if ($entity === 'family_member') {
            header('Content-Type: application/json');

            $sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;
            $secondary_id = isset($_POST['secondary_family_member_id']) && $_POST['secondary_family_member_id'] !== '' ? (int)$_POST['secondary_family_member_id'] : null;

            if ($sin === null) {
                echo json_encode(['success' => false, 'message' => 'SIN is required.']);
                break;
            }

            $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
            $checkPerson->bind_param("s", $sin);
            $checkPerson->execute();
            $checkPerson->store_result();

            if ($checkPerson->num_rows === 0) {
                $insertPerson = $conn->prepare("INSERT INTO Person (sin) VALUES (?)");
                if (!$insertPerson->bind_param("s", $sin) || !$insertPerson->execute()) {
                    echo json_encode([
                        'success' => false,
                        'message' => "Failed to insert new Person with SIN '$sin'."
                    ]);
                    break;
                }
            }

            if ($secondary_id !== null) {
                $checkSecondary = $conn->prepare("SELECT 1 FROM SecondaryFamilyMember WHERE secondary_family_member_id = ?");
                $checkSecondary->bind_param("i", $secondary_id);
                $checkSecondary->execute();
                $checkSecondary->store_result();

                if ($checkSecondary->num_rows === 0) {
                    $insertSecondary = $conn->prepare("INSERT INTO SecondaryFamilyMember (secondary_family_member_id) VALUES (?)");
                    $insertSecondary->bind_param("i", $secondary_id);
                    if (!$insertSecondary->execute()) {
                        echo json_encode([
                            'success' => false,
                            'message' => "Failed to auto-insert Secondary Family Member ID '$secondary_id'."
                        ]);
                        break;
                    }
                }
            }


            $stmt = $conn->prepare("INSERT INTO FamilyMember (sin, secondary_family_member_id) VALUES (?, ?)");
            $stmt->bind_param("si", $sin, $secondary_id);

            if ($stmt->execute()) {
                $family_member_id = $conn->insert_id;

                ob_start();
                displayFamilyMemberRowOnly($conn, $family_member_id);
                $rowHTML = ob_get_clean();

                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Insert failed: ' . htmlspecialchars($stmt->error)]);
            }
        }


        if ($entity === 'club_member') {
            header('Content-Type: application/json');

            $sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;
            $height = isset($_POST['height']) ? (int)$_POST['height'] : null;
            $weight = isset($_POST['weight']) ? (int)$_POST['weight'] : null;
            $status = isset($_POST['status']) && $_POST['status'] !== '' ? $_POST['status'] : 'Active';
            $current_location_id = isset($_POST['current_location_id']) && $_POST['current_location_id'] !== '' ? (int)$_POST['current_location_id'] : null;
            $deactivation_date = isset($_POST['deactivation_date']) && $_POST['deactivation_date'] !== '' ? $_POST['deactivation_date'] : null;
            $last_location_id = isset($_POST['last_location_id']) && $_POST['last_location_id'] !== '' ? (int)$_POST['last_location_id'] : null;
            $last_role = isset($_POST['last_role']) && $_POST['last_role'] !== '' ? $_POST['last_role'] : null;

            if ($sin === null) {
                echo json_encode(['success' => false, 'message' => 'SIN is required.']);
                break;
            }

            $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
            $checkPerson->bind_param("s", $sin);
            $checkPerson->execute();
            $checkPerson->store_result();

            if ($checkPerson->num_rows === 0) {
                $insertPerson = $conn->prepare("INSERT INTO Person (sin) VALUES (?)");
                $insertPerson->bind_param("s", $sin);
                if (!$insertPerson->execute()) {
                    echo json_encode(['success' => false, 'message' => "Failed to auto-insert Person with SIN '$sin'."]);
                    break;
                }
            }

            if ($current_location_id !== null) {
                $checkLoc = $conn->prepare("SELECT 1 FROM Location WHERE location_id = ?");
                $checkLoc->bind_param("i", $current_location_id);
                $checkLoc->execute();
                $checkLoc->store_result();

                if ($checkLoc->num_rows === 0) {
                    $insertLoc = $conn->prepare("INSERT INTO Location (name, type, address) VALUES ('Auto-Gen', 'Branch', 'Unknown')");
                    if (!$insertLoc->execute()) {
                        echo json_encode(['success' => false, 'message' => "Failed to auto-insert Location ID '$current_location_id'."]);
                        break;
                    }
                    $current_location_id = $conn->insert_id;
                }
            }

            $stmt = $conn->prepare("INSERT INTO ClubMember (sin, height, weight, status, current_location_id, deactivation_date, last_location_id, last_role)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisssis", $sin, $height, $weight, $status, $current_location_id, $deactivation_date, $last_location_id, $last_role);

            if ($stmt->execute()) {
                $club_member_id = $conn->insert_id;

                ob_start();
                displayClubMemberRowOnly($conn, $club_member_id);
                $rowHTML = ob_get_clean();

                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Insert failed: ' . htmlspecialchars($stmt->error)]);
            }
        }


        if ($entity === 'team') {
            header('Content-Type: application/json');

            $name = isset($_POST['name']) && $_POST['name'] !== '' ? $_POST['name'] : null;
            $location_id = isset($_POST['location_id']) ? (int)$_POST['location_id'] : null;
            $gender = isset($_POST['gender']) && $_POST['gender'] !== '' ? $_POST['gender'] : null;
            $captain_id = isset($_POST['captain_id']) ? (int)$_POST['captain_id'] : null;

            if ($name === null || $gender === null) {
                echo json_encode(['success' => false, 'message' => 'Team name and gender are required.']);
                break;
            }

            if ($location_id !== null) {
                $checkLocation = $conn->prepare("SELECT 1 FROM Location WHERE location_id = ?");
                $checkLocation->bind_param("i", $location_id);
                $checkLocation->execute();
                $checkLocation->store_result();

                if ($checkLocation->num_rows === 0) {
                    $placeholderName = "AutoLoc-$location_id";
                    $placeholderType = "Branch";
                    $placeholderAddress = "Auto Address $location_id";

                    $insertLocation = $conn->prepare("INSERT INTO Location (location_id, name, type, address) VALUES (?, ?, ?, ?)");
                    $insertLocation->bind_param("isss", $location_id, $placeholderName, $placeholderType, $placeholderAddress);
                    if (!$insertLocation->execute()) {
                        echo json_encode(['success' => false, 'message' => "Failed to create Location with ID $location_id."]);
                        break;
                    }
                }
            }

            if ($captain_id !== null) {
                $checkCaptain = $conn->prepare("SELECT 1 FROM ClubMember WHERE club_member_id = ?");
                $checkCaptain->bind_param("i", $captain_id);
                $checkCaptain->execute();
                $checkCaptain->store_result();

                if ($checkCaptain->num_rows === 0) {
                    $autoSin = "8" . str_pad($captain_id, 8, '0', STR_PAD_LEFT);

                    $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
                    $checkPerson->bind_param("s", $autoSin);
                    $checkPerson->execute();
                    $checkPerson->store_result();

                    if ($checkPerson->num_rows === 0) {
                        $insertPerson = $conn->prepare("INSERT INTO Person (sin) VALUES (?)");
                        $insertPerson->bind_param("s", $autoSin);
                        if (!$insertPerson->execute()) {
                            echo json_encode(['success' => false, 'message' => "Failed to create Person for captain SIN $autoSin."]);
                            break;
                        }
                    }

                    $insertClubMember = $conn->prepare("INSERT INTO ClubMember (club_member_id, sin, height, weight, status) VALUES (?, ?, 170, 70, 'Active')");
                    $insertClubMember->bind_param("is", $captain_id, $autoSin);
                    if (!$insertClubMember->execute()) {
                        echo json_encode(['success' => false, 'message' => "Failed to create ClubMember for captain ID $captain_id."]);
                        break;
                    }
                }
            }

            $stmt = $conn->prepare("INSERT INTO Team (name, location_id, gender, captain_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sisi", $name, $location_id, $gender, $captain_id);

            if ($stmt->execute()) {
                $team_id = $conn->insert_id;

                ob_start();
                displayTeamRowOnly($conn, $team_id);
                $rowHTML = ob_get_clean();

                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Insert failed: ' . htmlspecialchars($stmt->error)]);
            }
        }


        if ($entity === 'team_member') {
            header('Content-Type: application/json');

            $team_id = isset($_POST['team_id']) ? (int)$_POST['team_id'] : null;
            $club_member_id = isset($_POST['club_member_id']) ? (int)$_POST['club_member_id'] : null;
            $role = isset($_POST['role']) && $_POST['role'] !== '' ? $_POST['role'] : 'Player';

            if ($team_id === null || $club_member_id === null) {
                echo json_encode(['success' => false, 'message' => 'Team ID and Club Member ID are required.']);
                break;
            }

            $checkTeam = $conn->prepare("SELECT 1 FROM Team WHERE team_id = ?");
            $checkTeam->bind_param("i", $team_id);
            $checkTeam->execute();
            $checkTeam->store_result();

            if ($checkTeam->num_rows === 0) {
                $teamName = "AutoTeam-$team_id";
                $gender = "Coed";
                $insertTeam = $conn->prepare("INSERT INTO Team (team_id, name, gender) VALUES (?, ?, ?)");
                $insertTeam->bind_param("iss", $team_id, $teamName, $gender);
                if (!$insertTeam->execute()) {
                    echo json_encode(['success' => false, 'message' => "Failed to auto-create team with ID $team_id."]);
                    break;
                }
            }

            $checkClub = $conn->prepare("SELECT sin FROM ClubMember WHERE club_member_id = ?");
            $checkClub->bind_param("i", $club_member_id);
            $checkClub->execute();
            $checkClub->store_result();

            if ($checkClub->num_rows === 0) {
                $autoSin = "9" . str_pad($club_member_id, 8, '0', STR_PAD_LEFT);

                $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
                $checkPerson->bind_param("s", $autoSin);
                $checkPerson->execute();
                $checkPerson->store_result();

                if ($checkPerson->num_rows === 0) {
                    $insertPerson = $conn->prepare("INSERT INTO Person (sin) VALUES (?)");
                    $insertPerson->bind_param("s", $autoSin);
                    if (!$insertPerson->execute()) {
                        echo json_encode(['success' => false, 'message' => "Failed to auto-create Person for SIN $autoSin."]);
                        break;
                    }
                }

                $insertClub = $conn->prepare("INSERT INTO ClubMember (club_member_id, sin, height, weight, status) VALUES (?, ?, 160, 60, 'Active')");
                $insertClub->bind_param("is", $club_member_id, $autoSin);
                if (!$insertClub->execute()) {
                    echo json_encode(['success' => false, 'message' => "Failed to auto-create ClubMember for ID $club_member_id."]);
                    break;
                }
            }

            $stmt = $conn->prepare("INSERT INTO TeamMember (team_id, club_member_id, role) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $team_id, $club_member_id, $role);

            if ($stmt->execute()) {
                ob_start();
                displayTeamMemberRowOnly($conn, $team_id, $club_member_id);
                $rowHTML = ob_get_clean();

                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Insert failed: ' . htmlspecialchars($stmt->error)]);
            }
        }


        break;


    case 'edit':
        header('Content-Type: application/json');

        if ($entity === 'location') {
            $location_id = isset($_POST['location_id']) ? $_POST['location_id'] : '';
            if ($location_id === '') {
                echo json_encode(['success' => false, 'message' => 'Location ID is required.']);
                break;
            }

            if (!locationExists($conn, $location_id)) {
                echo json_encode(['success' => false, 'message' => "No location found with ID $location_id."]);
                break;
            }

            $fields = [
                'name'         => isset($_POST['name']) ? $_POST['name'] : null,
                'type'         => isset($_POST['type']) ? $_POST['type'] : null,
                'address'      => isset($_POST['address']) ? $_POST['address'] : null,
                'phone_number' => isset($_POST['phone_number']) ? $_POST['phone_number'] : null,
                'web_address'  => isset($_POST['web_address']) ? $_POST['web_address'] : null,
                'max_capacity' => isset($_POST['max_capacity']) && $_POST['max_capacity'] !== '' ? (int)$_POST['max_capacity'] : null,
            ];

            $setClause = [];
            $params = [];
            $types = '';

            foreach ($fields as $col => $val) {
                if ($val !== null && $val !== '') {
                    $setClause[] = "$col = ?";
                    $params[] = $val;
                    $types .= ($col === 'max_capacity') ? 'i' : 's';
                }
            }

            if (empty($setClause)) {
                echo json_encode(['success' => false, 'message' => 'No editable fields provided.']);
                break;
            }

            $sql = "UPDATE Location SET " . implode(", ", $setClause) . " WHERE location_id = ?";
            $params[] = $location_id;
            $types .= 'i';

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'SQL prepare failed: ' . $conn->error]);
                break;
            }

            $bindParams = [];
            $bindParams[] = $types;
            foreach ($params as $i => $val) {
                $bindParams[] = &$params[$i];
            }

            call_user_func_array([$stmt, 'bind_param'], $bindParams);

            if ($stmt->execute()) {
                ob_start();
                displayLocationRowOnly($conn, $location_id);
                $rowHTML = ob_get_clean();
                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
            }

            exit;
        }

        if ($entity === 'personnel') {
            $personnel_id = isset($_POST['personnel_id']) ? (int)$_POST['personnel_id'] : null;
            $new_sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;

            if (!$personnel_id) {
                echo json_encode(['success' => false, 'message' => 'Personnel ID is required.']);
                break;
            }

            if ($new_sin === null) {
                echo json_encode(['success' => false, 'message' => 'SIN is required for update.']);
                break;
            }

            // Step 1: Get the current SIN
            $getSinStmt = $conn->prepare("SELECT sin FROM Personnel WHERE personnel_id = ?");
            $getSinStmt->bind_param("i", $personnel_id);
            $getSinStmt->execute();
            $getSinStmt->bind_result($current_sin);
            $getSinStmt->fetch();
            $getSinStmt->close();

            if (!$current_sin) {
                echo json_encode(['success' => false, 'message' => 'Could not find current SIN.']);
                break;
            }

            // Step 2: Check if the new SIN already exists (and isn't the same as current)
            if ($current_sin !== $new_sin) {
                $checkDuplicate = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
                $checkDuplicate->bind_param("s", $new_sin);
                $checkDuplicate->execute();
                $checkDuplicate->store_result();

                if ($checkDuplicate->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => "SIN '$new_sin' is already in use."]);
                    break;
                }
            }

            // Step 3: Update Person table's SIN (cascades to Personnel via ON UPDATE CASCADE)
            $updateStmt = $conn->prepare("UPDATE Person SET sin = ? WHERE sin = ?");
            $updateStmt->bind_param("ss", $new_sin, $current_sin);

            if ($updateStmt->execute()) {
                ob_start();
                displayPersonnelRowOnly($conn, $personnel_id);
                $rowHTML = ob_get_clean();

                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed: ' . $updateStmt->error]);
            }

            break;
        }

        if ($entity === 'family_member') {
            $family_member_id = isset($_POST['family_member_id']) ? (int)$_POST['family_member_id'] : null;
            $new_sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;
            $new_secondary_id = isset($_POST['secondary_family_member_id']) ? (int)$_POST['secondary_family_member_id'] : null;

            if (!$family_member_id) {
                echo json_encode(['success' => false, 'message' => 'Family Member ID is required.']);
                break;
            }

            $fetch = $conn->prepare("SELECT sin FROM FamilyMember WHERE family_member_id = ?");
            $fetch->bind_param("i", $family_member_id);
            $fetch->execute();
            $fetchResult = $fetch->get_result();

            if ($fetchResult->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Family Member not found.']);
                break;
            }

            $row = $fetchResult->fetch_assoc();
            $old_sin = $row['sin'];

            if ($new_sin !== null && $new_sin !== $old_sin) {
                $stmt = $conn->prepare("UPDATE Person SET sin = ? WHERE sin = ?");
                $stmt->bind_param("ss", $new_sin, $old_sin);

                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'SIN update failed: ' . $stmt->error]);
                    break;
                }
            }

            if ($new_secondary_id !== null) {
                $stmt = $conn->prepare("UPDATE FamilyMember SET secondary_family_member_id = ? WHERE family_member_id = ?");
                $stmt->bind_param("ii", $new_secondary_id, $family_member_id);

                if (!$stmt->execute()) {
                    echo json_encode(['success' => false, 'message' => 'Secondary contact update failed: ' . $stmt->error]);
                    break;
                }
            }

            ob_start();
            displayFamilyMemberRowOnly($conn, $family_member_id);
            $rowHTML = ob_get_clean();
            echo json_encode(['success' => true, 'row' => $rowHTML]);
            break;
        }


        if ($entity === 'club_member') {
            $club_member_id = isset($_POST['club_member_id']) ? (int)$_POST['club_member_id'] : null;
            $new_sin = isset($_POST['sin']) && $_POST['sin'] !== '' ? $_POST['sin'] : null;

            if (!$club_member_id) {
                echo json_encode(['success' => false, 'message' => 'Club Member ID is required.']);
                break;
            }

            $getSinStmt = $conn->prepare("SELECT sin FROM ClubMember WHERE club_member_id = ?");
            $getSinStmt->bind_param("i", $club_member_id);
            $getSinStmt->execute();
            $getSinStmt->bind_result($old_sin);
            $getSinStmt->fetch();
            $getSinStmt->close();

            if (!$old_sin) {
                echo json_encode(['success' => false, 'message' => 'Club member not found.']);
                break;
            }


            if ($new_sin && $new_sin !== $old_sin) {
                $checkPerson = $conn->prepare("SELECT 1 FROM Person WHERE sin = ?");
                $checkPerson->bind_param("s", $new_sin);
                $checkPerson->execute();
                $checkPerson->store_result();

                if ($checkPerson->num_rows > 0) {
                    echo json_encode(['success' => false, 'message' => "SIN '$new_sin' already exists in Person table."]);
                    break;
                }

                // Update Person's SIN — will cascade to ClubMember (thanks to ON UPDATE CASCADE)
                $updateSinStmt = $conn->prepare("UPDATE Person SET sin = ? WHERE sin = ?");
                $updateSinStmt->bind_param("ss", $new_sin, $old_sin);

                if (!$updateSinStmt->execute()) {
                    echo json_encode(['success' => false, 'message' => "Failed to update SIN: " . $updateSinStmt->error]);
                    break;
                }
            }

            $fields = [
                'height' => isset($_POST['height']) && $_POST['height'] !== '' ? (int)$_POST['height'] : null,
                'weight' => isset($_POST['weight']) && $_POST['weight'] !== '' ? (int)$_POST['weight'] : null,
                'status' => isset($_POST['status']) && $_POST['status'] !== '' ? $_POST['status'] : null,
                'deactivation_date' => isset($_POST['deactivation_date']) && $_POST['deactivation_date'] !== '' ? $_POST['deactivation_date'] : null,
                'last_role' => isset($_POST['last_role']) && $_POST['last_role'] !== '' ? $_POST['last_role'] : null,
                'current_location_id' => isset($_POST['current_location_id']) && $_POST['current_location_id'] !== '' ? (int)$_POST['current_location_id'] : null,
                'last_location_id' => isset($_POST['last_location_id']) && $_POST['last_location_id'] !== '' ? (int)$_POST['last_location_id'] : null,
            ];

            foreach (['current_location_id', 'last_location_id'] as $locKey) {
                if ($fields[$locKey] !== null) {
                    $checkLoc = $conn->prepare("SELECT 1 FROM Location WHERE location_id = ?");
                    $checkLoc->bind_param("i", $fields[$locKey]);
                    $checkLoc->execute();
                    $checkLoc->store_result();
                    if ($checkLoc->num_rows === 0) {
                        echo json_encode(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $locKey)) . " not found."]);
                        break 2;
                    }
                }
            }

            $setClause = [];
            $params = [];
            $types = '';

            foreach ($fields as $col => $val) {
                if ($val !== null) {
                    $setClause[] = "$col = ?";
                    $params[] = $val;
                    $types .= in_array($col, ['height', 'weight', 'current_location_id', 'last_location_id']) ? 'i' : 's';
                }
            }

            if (empty($setClause)) {
                echo json_encode(['success' => false, 'message' => 'No editable fields provided.']);
                break;
            }

            $params[] = $club_member_id;
            $types .= 'i';

            $sql = "UPDATE ClubMember SET " . implode(", ", $setClause) . " WHERE club_member_id = ?";
            $stmt = $conn->prepare($sql);
            $bindParams = array_merge([$types], $params);
            $refs = [];
            foreach ($bindParams as $k => $v) $refs[$k] = &$bindParams[$k];
            call_user_func_array([$stmt, 'bind_param'], $refs);

            if ($stmt->execute()) {
                ob_start();
                displayClubMemberRowOnly($conn, $club_member_id);
                $rowHTML = ob_get_clean();
                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
            }

            break;
        }


        if ($entity === 'team') {
            $team_id = isset($_POST['team_id']) ? (int)$_POST['team_id'] : null;

            if (!$team_id) {
                echo json_encode(['success' => false, 'message' => 'Team ID is required.']);
                break;
            }

            $fields = [
                'name'        => isset($_POST['name']) && $_POST['name'] !== '' ? $_POST['name'] : null,
                'gender'      => isset($_POST['gender']) && $_POST['gender'] !== '' ? $_POST['gender'] : null,
                'location_id' => isset($_POST['location_id']) && $_POST['location_id'] !== '' ? (int)$_POST['location_id'] : null,
                'captain_id'  => isset($_POST['captain_id']) && $_POST['captain_id'] !== '' ? (int)$_POST['captain_id'] : null
            ];

            foreach (['location_id', 'captain_id'] as $fk) {
                if ($fields[$fk] !== null) {
                    $refTable = $fk === 'location_id' ? 'Location' : 'ClubMember';
                    $refColumn = $fk === 'location_id' ? 'location_id' : 'club_member_id';

                    $check = $conn->prepare("SELECT 1 FROM $refTable WHERE $refColumn = ?");
                    if (!$check) {
                        echo json_encode(['success' => false, 'message' => "SQL prepare failed: " . $conn->error]);
                        break 2;
                    }
                    $check->bind_param("i", $fields[$fk]);
                    $check->execute();
                    $check->store_result();
                    if ($check->num_rows === 0) {
                        echo json_encode(['success' => false, 'message' => ucfirst($fk) . " '{$fields[$fk]}' does not exist."]);
                        break 2;
                    }
                }
            }

            $setClause = [];
            $params = [];
            $types = '';

            foreach ($fields as $col => $val) {
                if ($val !== null) {
                    $setClause[] = "$col = ?";
                    $params[] = $val;
                    $types .= in_array($col, ['location_id', 'captain_id']) ? 'i' : 's';
                }
            }

            if (empty($setClause)) {
                echo json_encode(['success' => false, 'message' => 'No editable fields provided.']);
                break;
            }

            $params[] = $team_id;
            $types .= 'i';

            $sql = "UPDATE Team SET " . implode(", ", $setClause) . " WHERE team_id = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'SQL prepare failed: ' . $conn->error]);
                break;
            }

            $bindParams = array_merge([$types], $params);
            $refs = [];
            foreach ($bindParams as $k => $v) {
                $refs[$k] = &$bindParams[$k];
            }

            call_user_func_array([$stmt, 'bind_param'], $refs);

            if ($stmt->execute()) {
                ob_start();
                displayTeamRowOnly($conn, $team_id);
                $rowHTML = ob_get_clean();
                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
            }

            break;
        }

        if ($entity === 'team_member') {
            $team_id = isset($_POST['team_id']) ? (int)$_POST['team_id'] : null;
            $club_member_id = isset($_POST['club_member_id']) ? (int)$_POST['club_member_id'] : null;
            $role = isset($_POST['role']) && $_POST['role'] !== '' ? $_POST['role'] : 'Player';

            if ($team_id === null || $club_member_id === null) {
                echo json_encode(['success' => false, 'message' => 'Team ID and Club Member ID are required.']);
                break;
            }

            $entityToTableMap = [
                'team' => 'Team',
                'club_member' => 'ClubMember'
            ];

            foreach (['team' => $team_id, 'club_member' => $club_member_id] as $entity => $id) {
                $col = $entity === 'team' ? 'team_id' : 'club_member_id';
                $table = $entityToTableMap[$entity];

                $check = $conn->prepare("SELECT 1 FROM $table WHERE $col = ?");
                if (!$check) {
                    echo json_encode(['success' => false, 'message' => 'SQL error during entity existence check: ' . $conn->error]);
                    break 2;
                }
                $check->bind_param("i", $id);
                $check->execute();
                $check->store_result();

                if ($check->num_rows === 0) {
                    echo json_encode(['success' => false, 'message' => ucfirst($col) . " $id does not exist."]);
                    break 2;
                }
            }

            // Check for scheduling conflict (sessions involving same date and < 3h apart)
            // $conflictCheck = $conn->prepare("
            //     SELECT 1
            //     FROM Session s1
            //     JOIN TeamMember tm ON tm.team_id IN (s1.team1_id, s1.team2_id)
            //     JOIN Session s2 ON s2.team1_id = ? OR s2.team2_id = ?
            //     WHERE tm.club_member_id = ?
            //     AND s1.date = s2.date
            //     AND ABS(TIMESTAMPDIFF(MINUTE, s1.start_time, s2.start_time)) < 180
            // ");
            // $conflictCheck->bind_param("iii", $team_id, $team_id, $club_member_id);
            // $conflictCheck->execute();
            // $conflictCheck->store_result();

            // if ($conflictCheck->num_rows > 0) {
            //     echo json_encode(['success' => false, 'message' => 'Scheduling conflict: This player is already assigned to a session within 3 hours of this one.']);
            //     break;
            // }

            $stmt = $conn->prepare("REPLACE INTO TeamMember (team_id, club_member_id, role) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $team_id, $club_member_id, $role);

            if ($stmt->execute()) {
                ob_start();
                displayTeamMemberRowOnly($conn, $team_id, $club_member_id);
                $rowHTML = ob_get_clean();
                echo json_encode(['success' => true, 'row' => $rowHTML]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Insert/update failed: ' . htmlspecialchars($stmt->error)]);
            }

            break;
        }


        break;


    case 'delete':
        if ($entity === 'location') {
            $location_id = $_POST['location_id'];
            if (!locationExists($conn, $location_id)) {
                echo json_encode(['success' => false, 'message' => 'No such location exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM Location WHERE location_id = ?");
            $stmt->bind_param("i", $location_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Location deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        if ($entity === 'personnel') {
            $personnel_id = $_POST['personnel_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Personnel WHERE personnel_id = ?");
            $stmt->bind_param("i", $personnel_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) {
                echo json_encode(['success' => false, 'message' => 'No such personnel exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM Personnel WHERE personnel_id = ?");
            $stmt->bind_param("i", $personnel_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Personnel deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        if ($entity === 'club_member') {
            $club_member_id = $_POST['club_member_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) FROM ClubMember WHERE club_member_id = ?");
            $stmt->bind_param("i", $club_member_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) {
                echo json_encode(['success' => false, 'message' => 'No such club member exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM ClubMember WHERE club_member_id = ?");
            $stmt->bind_param("i", $club_member_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Club member deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        if ($entity === 'team') {
            $team_id = $_POST['team_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Team WHERE team_id = ?");
            $stmt->bind_param("i", $team_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) {
                echo json_encode(['success' => false, 'message' => 'No such team exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM Team WHERE team_id = ?");
            $stmt->bind_param("i", $team_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Team deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        if ($entity === 'family_member') {
            $family_member_id = $_POST['family_member_id'];
            $stmt = $conn->prepare("SELECT COUNT(*) FROM FamilyMember WHERE family_member_id = ?");
            $stmt->bind_param("i", $family_member_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) {
                echo json_encode(['success' => false, 'message' => 'No such family member exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM FamilyMember WHERE family_member_id = ?");
            $stmt->bind_param("i", $family_member_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Family member deleted successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        if ($entity === 'team_member') {
            $team_id = $_POST['team_id'];
            $club_member_id = $_POST['club_member_id'];

            $stmt = $conn->prepare("SELECT COUNT(*) FROM TeamMember WHERE team_id = ? AND club_member_id = ?");
            $stmt->bind_param("ii", $team_id, $club_member_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count === 0) {
                echo json_encode(['success' => false, 'message' => 'No such team-member combination exists to delete.']);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM TeamMember WHERE team_id = ? AND club_member_id = ?");
            $stmt->bind_param("ii", $team_id, $club_member_id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Team member unassigned successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Deletion failed: ' . htmlspecialchars($stmt->error) . '.']);
            }
            exit;
        }

        break;

    case 'populate':

        switch ($entity) {
            case 'location':
                $stmt = $conn->query("SELECT location_id, name FROM Location");
                $locations = [];
                while ($row = $stmt->fetch_assoc()) {
                    $locations[] = [
                        'value' => $row['location_id'],
                        'label' => $row['name']
                    ];
                }
                echo json_encode($locations);
                break;

            case 'club_member':
                $stmt = $conn->query("SELECT club_member_id, sin FROM ClubMember");
                $members = [];
                while ($row = $stmt->fetch_assoc()) {
                    $members[] = [
                        'value' => $row['club_member_id'],
                        'label' => $row['club_member_id'] . ' (SIN: ' . $row['sin'] . ')'
                    ];
                }
                echo json_encode($members);
                break;

            case 'team':
                $stmt = $conn->query("SELECT team_id, name FROM Team");
                $teams = [];
                while ($row = $stmt->fetch_assoc()) {
                    $teams[] = [
                        'value' => $row['team_id'],
                        'label' => $row['name']
                    ];
                }
                echo json_encode($teams);
                break;

            case 'secondary_family_member':
                $stmt = $conn->query("SELECT secondary_family_member_id FROM SecondaryFamilyMember");
                $secfam = [];
                while ($row = $stmt->fetch_assoc()) {
                    $secfam[] = [
                        'value' => $row['secondary_family_member_id'],
                        'label' => 'Secondary #' . $row['secondary_family_member_id']
                    ];
                }
                echo json_encode($secfam);
                break;

            case 'personnel':
                $stmt = $conn->query("SELECT personnel_id, sin FROM Personnel");
                $personnel = [];
                while ($row = $stmt->fetch_assoc()) {
                    $personnel[] = [
                        'value' => $row['personnel_id'],
                        'label' => 'ID: ' . $row['personnel_id'] . ' (SIN: ' . $row['sin'] . ')'
                    ];
                }
                echo json_encode($personnel);
                break;

            default:
                echo json_encode(['error' => 'Invalid entity for populate']);
        }
        break;

    default:
        echo "<p class='error-msg'>Invalid action or entity specified.</p>";
        break;
}

function locationExists($conn, $location_id) {
    $count = 0;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Location WHERE location_id = ?");
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return $count > 0;
}

function debugPostDataAndExit() {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Debugging POST data',
        'debug' => $_POST
    ]);
    exit;
}



function displayLocationRowOnly($conn, $location_id) {
    $stmt = $conn->prepare("SELECT * FROM Location WHERE location_id = ?");
    $stmt->bind_param("i", $location_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";
        echo "<td>
                <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'location', true)\">
                    <i class='bi bi-pencil-square'>EDIT</i>
                </button>
                <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'location')\">
                    <i class='bi bi-trash'>DEL</i>
                </button>
            </td>";

        foreach ($row as $val) {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
}



function displayPersonnelRowOnly($conn, $personnel_id) {
    $stmt = $conn->prepare("SELECT * FROM Personnel WHERE personnel_id = ?");
    $stmt->bind_param("i", $personnel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;
    $row = $result->fetch_assoc();

    echo "<tr>";
    echo "<td>
        <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'personnel', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
        <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'personnel')\"><i class=\"bi bi-trash\">DEL</i></button>
    </td>";
    foreach ($row as $val) {
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }
    echo "</tr>";
}

function displayFamilyMemberRowOnly($conn, $family_member_id) {
    $stmt = $conn->prepare("SELECT * FROM FamilyMember WHERE family_member_id = ?");
    $stmt->bind_param("i", $family_member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;
    $row = $result->fetch_assoc();

    echo "<tr>";
    echo "<td>
        <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'family_member', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
        <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'family_member')\"><i class=\"bi bi-trash\">DEL</i></button>
    </td>";
    foreach ($row as $val) {
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }
    echo "</tr>";
}

function displayClubMemberRowOnly($conn, $club_member_id) {
    $stmt = $conn->prepare("SELECT * FROM ClubMember WHERE club_member_id = ?");
    $stmt->bind_param("i", $club_member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;
    $row = $result->fetch_assoc();

    echo "<tr>";
    echo "<td>
        <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'club_member', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
        <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'club_member')\"><i class=\"bi bi-trash\">DEL</i></button>
    </td>";
    foreach ($row as $val) {
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }
    echo "</tr>";
}

function displayTeamRowOnly($conn, $team_id) {
    $stmt = $conn->prepare("SELECT * FROM Team WHERE team_id = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;
    $row = $result->fetch_assoc();

    echo "<tr>";
    echo "<td>
        <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'team', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
        <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'team')\"><i class=\"bi bi-trash\">DEL</i></button>
    </td>";
    foreach ($row as $val) {
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }
    echo "</tr>";
}

function displayTeamMemberRowOnly($conn, $team_id, $club_member_id) {
    $stmt = $conn->prepare("SELECT * FROM TeamMember WHERE team_id = ? AND club_member_id = ?");
    $stmt->bind_param("ii", $team_id, $club_member_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) return;
    $row = $result->fetch_assoc();

    echo "<tr>";
    echo "<td>
        <button class='action-btn edit-btn' onclick=\"createOrEditRow(this, 'team_member', true)\"><i class=\"bi bi-pencil-square\">EDIT</i></button>
        <button class='action-btn delete-btn' onclick=\"deleteRow(this, 'team_member')\"><i class=\"bi bi-trash\">DEL</i></button>
    </td>";
    foreach ($row as $val) {
        echo "<td>" . htmlspecialchars($val) . "</td>";
    }
    echo "</tr>";
}

?>
