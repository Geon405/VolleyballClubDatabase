USE lqc353_4;

-- Disable FK checks to avoid constraint errors during bulk TRUNCATE
SET FOREIGN_KEY_CHECKS = 0;

-- Clear all tables
TRUNCATE TABLE EmailLog;
TRUNCATE TABLE Payments;
TRUNCATE TABLE Session;
TRUNCATE TABLE TeamMember;
TRUNCATE TABLE Team;
TRUNCATE TABLE ClubMemberSecondaryRelationship;
TRUNCATE TABLE ClubMemberFamily;
TRUNCATE TABLE ClubMember;
TRUNCATE TABLE SecondaryFamilyMember;
TRUNCATE TABLE FamilyMember;
TRUNCATE TABLE OperatesAt;
TRUNCATE TABLE Personnel;
TRUNCATE TABLE Person;
TRUNCATE TABLE Location;
TRUNCATE TABLE PostalCode;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO PostalCode (postal_code, city, province) VALUES
('H1A1A1', 'Montreal', 'QC'),
('H7A1B2', 'Laval', 'QC'),
('H9P3H6', 'Dorval', 'QC'),
('J4V2H1', 'Longueuil', 'QC'),
('G1R1S8', 'Quebec City', 'QC'),
('J1H1R5', 'Sherbrooke', 'QC'),
('G9A2B3', 'Trois-Rivières', 'QC'),
('J8X3Y2', 'Gatineau', 'QC'),
('J4W2G6', 'Brossard', 'QC'),
('J2B5R9', 'Drummondville', 'QC'),
('G7H3A4', 'Chicoutimi', 'QC'),
('H1A1B1', 'Montreal', 'QC'),
('H7A1C2', 'Laval', 'QC'),
('H9S2X3', 'Dorval', 'QC'),
('H1C2B3', 'Montreal', 'QC'),
('J4V1W2', 'Longueuil', 'QC'),
('H7A2D3', 'Laval', 'QC'),
('H9H3L2', 'Dorval', 'QC'),
('H1A2D4', 'Montreal', 'QC'),
('H7B3K5', 'Laval', 'QC'),
('H3G1P4', 'Montreal', 'QC'),
('H3G1Z7', 'Montreal', 'QC'),
('H3G1P1', 'Montreal', 'QC');

INSERT INTO Person (sin, first_name, last_name, birth_date, medicare_card_number, telephone_number, email_address, address, postal_code)
VALUES
('900000001', 'Alice', 'Johnson', '1982-03-15', 'MED900000001', '5141001001', 'alice.j@email.com', '101 Elm St', 'H1A1A1'),
('900000002', 'Bob', 'Smith', '1978-07-23', 'MED900000002', '5141001002', 'bob.s@email.com', '102 Oak St', 'H1A1A1'),
('900000003', 'Cathy', 'Nguyen', '1990-10-11', 'MED900000003', '5141001003', 'cathy.n@email.com', '103 Pine St', 'H7A1B2'),
('900000004', 'David', 'Lee', '1985-04-05', 'MED900000004', '5141001004', 'david.lee@email.com', '104 Birch St', 'H7A1B2'),
('900000005', 'Eva', 'Khan', '1992-11-30', 'MED900000005', '5141001005', 'eva.k@email.com', '105 Maple Ave', 'H9P3H6'),
('900000006', 'Frank', 'Wong', '1983-08-17', 'MED900000006', '5141001006', 'frank.w@email.com', '106 Cedar Ave', 'H9P3H6'),
('900000007', 'Grace', 'Martinez', '1986-02-12', 'MED900000007', '5141001007', 'grace.m@email.com', '107 Walnut St', 'J4V2H1'),
('900000008', 'Henry', 'Brown', '1984-06-25', 'MED900000008', '5141001008', 'henry.b@email.com', '108 Poplar Ln', 'J4V2H1'),
('900000009', 'Ivy', 'Lopez', '1995-09-09', 'MED900000009', '5141001009', 'ivy.l@email.com', '109 Chestnut Blvd', 'G1R1S8'),
('900000010', 'Jake', 'White', '1989-12-01', 'MED900000010', '5141001010', 'jake.w@email.com', '110 Fir Dr', 'G1R1S8'),
('900000011', 'Karen', 'Taylor', '1981-05-20', 'MED900000011', '5141001011', 'karen.t@email.com', '111 Hemlock Way', 'J1H1R5'),
('900000012', 'Leo', 'Walker', '1979-03-03', 'MED900000012', '5141001012', 'leo.w@email.com', '112 Aspen Ct', 'J1H1R5'),
('900000013', 'Mona', 'Hall', '1993-10-10', 'MED900000013', '5141001013', 'mona.h@email.com', '113 Willow Cres', 'G9A2B3'),
('900000014', 'Nina', 'Bennett', '1991-06-28', 'MED900000014', '5141001014', 'nina.b@email.com', '114 Alder Rd', 'G9A2B3'),
('900000015', 'Oscar', 'Kim', '1987-01-17', 'MED900000015', '5141001015', 'oscar.k@email.com', '115 Magnolia St', 'J8X3Y2'),
('900000016', 'Paula', 'Diaz', '1994-11-11', 'MED900000016', '5141001016', 'paula.d@email.com', '116 Palm Ave', 'J8X3Y2'),
('900000017', 'Quinn', 'Singh', '1988-09-06', 'MED900000017', '5141001017', 'quinn.s@email.com', '117 Sycamore Pl', 'J4W2G6'),
('900000018', 'Raj', 'Chowdhury', '1980-02-22', 'MED900000018', '5141001018', 'raj.c@email.com', '118 Spruce Ter', 'J4W2G6'),
('900000019', 'Sara', 'Young', '1986-07-08', 'MED900000019', '5141001019', 'sara.y@email.com', '119 Redwood Blvd', 'J2B5R9'),
('900000020', 'Tom', 'Zhou', '1990-04-30', 'MED900000020', '5141001020', 'tom.z@email.com', '120 Olive Dr', 'J2B5R9');


INSERT INTO Personnel (sin)
VALUES
('900000001'),
('900000002'),
('900000003'),
('900000004'),
('900000005'),
('900000006'),
('900000007'),
('900000008'),
('900000009'),
('900000010'),
('900000011'),
('900000012'),
('900000013'),
('900000014'),
('900000015'),
('900000016'),
('900000017'),
('900000018'),
('900000019'),
('900000020');


INSERT INTO Location (type, name, address, postal_code, phone_number, web_address, max_capacity, general_manager_id)
VALUES
('Head', 'Montreal Main', '123 Main Street', 'H1A1A1', '5141234567', 'www.montrealmain.com', 500, 4),
('Branch', 'Laval Branch', '456 Laval Street', 'H7A1B2', '4501234567', 'www.lavalbranch.com', 200, 5),
('Branch', 'Dorval Branch', '789 Dorval Avenue', 'H9P3H6', '5147894561', 'www.dorvalbranch.com', 150, 6),
('Branch', 'Longueuil Branch', '321 Longueuil Blvd', 'J4V2H1', '4509876543', 'www.longueuilbranch.com', 300, 7),
('Branch', 'Quebec City Branch', '987 Rue Saint-Jean', 'G1R1S8', '4181237890', 'www.quebeccitybranch.com', 250, 8),
('Branch', 'Sherbrooke Branch', '654 King Street', 'J1H1R5', '8194567890', 'www.sherbrookebranch.com', 180, 9),
('Branch', 'Trois-Rivières Branch', '852 Mauricie Road', 'G9A2B3', '8196543210', 'www.troisrivieresbranch.com', 220, 10),
('Branch', 'Gatineau Branch', '741 Hull Street', 'J8X3Y2', '8197891234', 'www.gatineaubranch.com', 270, 11),
('Branch', 'Brossard Branch', '159 Brossard Blvd', 'J4W2G6', '4503219876', 'www.brossardbranch.com', 300, 12),
('Branch', 'Drummondville Branch', '357 Centre-Ville Avenue', 'J2B5R9', '8198765432', 'www.drummondvillebranch.com', 160, 13);


INSERT INTO OperatesAt (personnel_id, location_id, start_date, end_date, role, mandate)
VALUES
(1, 1, '2020-01-01', NULL, 'Coach', 'Salaried'),
(2, 2, '2021-06-15', NULL, 'Assistant Coach', 'Volunteer'),
(3, 3, '2022-03-01', NULL, 'Treasurer', 'Salaried'),
(4, 1, '2019-05-10', NULL, 'General Manager', 'Salaried'),
(5, 2, '2021-07-01', NULL, 'Deputy Manager', 'Volunteer'),
(6, 3, '2020-10-15', '2022-10-15', 'Administrator', 'Salaried'),
(6, 4, '2022-11-01', NULL, 'Administrator', 'Salaried'),
(7, 4, '2021-08-10', NULL, 'Coach', 'Salaried'),
(8, 5, '2022-02-20', NULL, 'Secretary', 'Volunteer'),
(9, 5, '2021-03-14', NULL, 'Other', 'Salaried'),
(10, 6, '2021-04-01', NULL, 'Captain', 'Salaried'),
(11, 7, '2023-01-01', NULL, 'Coach', 'Volunteer'),
(12, 8, '2020-11-11', NULL, 'Other', 'Salaried'),
(13, 9, '2019-07-07', NULL, 'Administrator', 'Volunteer'),
(14, 10, '2022-09-09', NULL, 'Coach', 'Salaried'),
(15, 1, '2023-02-02', NULL, 'Treasurer', 'Volunteer'),
(16, 2, '2022-06-06', NULL, 'Deputy Manager', 'Salaried'),
(17, 3, '2023-03-03', NULL, 'Coach', 'Volunteer'),
(18, 4, '2021-08-08', NULL, 'Other', 'Salaried'),
(19, 5, '2021-09-09', NULL, 'Secretary', 'Volunteer');

INSERT INTO SecondaryFamilyMember (first_name, last_name, phone_number, email_address)
VALUES
('Michael', 'Williams', '5141111000', 'michaelw@email.com'),
('Linda', 'Brown', '5141111001', 'lindab@email.com'),
('George', 'Nguyen', '5141111002', 'georgen@email.com'),
('Susan', 'Smith', '5141111003', 'susans@email.com'),
('Peter', 'Lopez', '5141111004', 'peterl@email.com'),
('Anna', 'White', '5141111005', 'annaw@email.com'),
('Charles', 'Khan', '5141111006', 'charlesk@email.com'),
('Rachel', 'Taylor', '5141111007', 'rachelt@email.com'),
('Victor', 'Zhou', '5141111008', 'victorz@email.com'),
('Megan', 'Singh', '5141111009', 'megans@email.com');

INSERT INTO FamilyMember (sin, secondary_family_member_id)
VALUES
('900000001', 1),
('900000002', 2),
('900000003', 3),
('900000004', 4),
('900000005', 5),
('900000006', 6),
('900000007', 7),
('900000008', 8),
('900000009', 9),
('900000010', 10);

INSERT INTO ClubMember (sin, height, weight, status, current_location_id, deactivation_date, last_location_id, last_role)
VALUES
('900000011', 170, 65, 'Active', 1, NULL, NULL, NULL),
('900000012', 165, 60, 'Active', 2, NULL, NULL, NULL),
('900000013', 172, 68, 'Inactive', 3, '2024-12-01', 3, 'Libero'),
('900000014', 168, 62, 'Active', 4, NULL, NULL, NULL),
('900000015', 175, 70, 'Active', 5, NULL, NULL, NULL),
('900000016', 160, 55, 'Inactive', 6, '2024-10-15', 6, 'Opposite'),
('900000017', 162, 58, 'Active', 7, NULL, NULL, NULL),
('900000018', 178, 72, 'Active', 8, NULL, NULL, NULL),
('900000019', 166, 64, 'Inactive', 9, '2025-01-05', 9, 'Setter'),
('900000020', 169, 66, 'Active', 10, NULL, NULL, NULL);


INSERT INTO ClubMemberFamily (club_member_id, family_member_id, relationship_type)
VALUES
(1, 1, 'Mother'),
(2, 2, 'Father'),
(3, 3, 'Grandmother'),
(4, 4, 'Tutor'),
(5, 5, 'Uncle'),
(6, 6, 'Aunt'),
(7, 7, 'Partner'),
(8, 8, 'Father'),
(9, 9, 'Mother'),
(10, 10, 'Other');


INSERT INTO ClubMemberSecondaryRelationship (club_member_id, secondary_family_member_id, relationship_type)
VALUES
(1, 1, 'Father'),
(2, 2, 'Mother'),
(3, 3, 'Uncle'),
(4, 4, 'Grandmother'),
(5, 5, 'Tutor'),
(6, 6, 'Aunt'),
(7, 7, 'Partner'),
(8, 8, 'Other'),
(9, 9, 'Father'),
(10, 10, 'Friend');


INSERT INTO Team (name, location_id, gender, captain_id)
VALUES
('Montreal Titans', 1, 'Coed', 1),
('Laval Lightning', 2, 'Female', 2),
('Dorval Hawks', 3, 'Male', 3),
('Longueuil Lynx', 4, 'Coed', 4),
('Quebec Falcons', 5, 'Female', 5),
('Sherbrooke Storm', 6, 'Male', 6),
('Trois-Rivières Tigers', 7, 'Coed', 7),
('Gatineau Gators', 8, 'Male', 8),
('Brossard Blizzards', 9, 'Female', 9),
('Drummondville Dragons', 10, 'Coed', 10);


INSERT INTO TeamMember (team_id, club_member_id, role)
VALUES
(1, 1, 'Setter'),
(1, 2, 'Outside Hitter'),
(2, 3, 'Middle Blocker'),
(2, 4, 'Libero'),
(3, 5, 'Defensive Specialist'),
(4, 6, 'Opposite'),
(4, 7, 'Outside Hitter'),
(5, 8, 'Middle Blocker'),
(6, 9, 'Libero'),
(7, 10, 'Setter'),
(7, 1, 'Outside Hitter'),
(8, 2, 'Opposite'),
(9, 3, 'Defensive Specialist'),
(10, 4, 'Outside Hitter');


INSERT INTO Session (type, date, start_time, location_id, team1_id, team2_id, score_team1, score_team2)
VALUES
('Training', '2025-03-20', '10:00:00', 1, 1, 2, NULL, NULL),
('Game', '2025-03-21', '14:00:00', 2, 3, 4, 21, 18),
('Training', '2025-03-22', '16:00:00', 3, 5, 6, NULL, NULL),
('Game', '2025-03-23', '18:00:00', 4, 7, 8, 25, 27),
('Game', '2025-03-24', '12:00:00', 5, 9, 10, 30, 28),
('Training', '2025-03-25', '09:00:00', 6, 1, 3, NULL, NULL),
('Game', '2025-03-26', '17:00:00', 7, 2, 4, 19, 25);


INSERT INTO Payments (club_member_id, payment_date, amount, payment_year, installment_number, payment_method)
VALUES
(1, '2025-01-15', 100, 2025, 1, 'Credit Card'),
(2, '2025-02-10', 50, 2025, 1, 'Cash'),
(2, '2025-03-10', 50, 2025, 2, 'Cash'),
(3, '2025-03-05', 75, 2025, 1, 'Debit Card'),
(4, '2025-04-01', 60, 2025, 1, 'Credit Card'),
(5, '2025-05-10', 70, 2025, 1, 'Debit Card'),
(6, '2025-06-01', 120, 2025, 1, 'Credit Card'),
(7, '2025-07-10', 90, 2025, 1, 'Cash'),
(8, '2025-08-20', 80, 2025, 1, 'Credit Card'),
(9, '2025-09-15', 100, 2025, 1, 'Debit Card'),
(10, '2025-10-01', 110, 2025, 1, 'Cash');


INSERT INTO EmailLog (recipient_email, sender, email_date, subject, mail_body, email_type)
VALUES
('emma.williams@email.com', 'admin@myvc.com', '2025-03-20 09:00:00', 'Team Formation Notice', 'You have been assigned to Montreal Titans for upcoming training.', 'formation'),
('bob.s@email.com', 'admin@myvc.com', '2025-03-24 08:45:00', 'Deactivation Notice', 'Your membership has been deactivated due to age eligibility.', 'deactivation'),
('grace.m@email.com', 'coach@myvc.com', '2025-03-25 11:00:00', 'Game Schedule Update', 'Your game has been rescheduled to March 26.', 'notification'),
('liam.brown@email.com', 'admin@myvc.com', '2025-03-19 10:30:00', 'Team Assignment', 'You have been added to Laval Lightning for this season.', 'formation'),
('ava.brown@email.com', 'admin@myvc.com', '2025-03-20 10:30:00', 'Team Assignment', 'You have been added to Laval Lightning as Outside Hitter.', 'formation'),
('noah.williams@email.com', 'admin@myvc.com', '2025-03-18 14:00:00', 'Payment Reminder', 'Your next membership installment is due March 30.', 'notification'),
('charlotte.davis@email.com', 'admin@myvc.com', '2025-03-22 09:00:00', 'Inactive Status Notice', 'You have been marked inactive due to missing payment.', 'deactivation'),
('sophia.miller@email.com', 'admin@myvc.com', '2025-03-23 11:45:00', 'Team Update', 'You have been transferred to Dorval Hawks.', 'formation'),
('lucas.johnson@email.com', 'coach@myvc.com', '2025-03-25 15:00:00', 'Practice Reminder', 'Practice starts at 6 PM sharp.', 'notification'),
('ethan.brown@email.com', 'admin@myvc.com', '2025-03-26 08:15:00', 'Eligibility Warning', 'You will be overage next season.', 'notification');
