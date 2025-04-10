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

INSERT INTO Person (sin, first_name, last_name, birth_date, medicare_card_number, telephone_number, email_address, address, postal_code) VALUES
('900000101', 'Aiden', 'Wong', '2010-06-15', 'MED900000101', '5142001101', 'aiden.w@email.com', '201 Ash Lane', 'H1A1A1'),
('900000102', 'Bella', 'Choi', '2011-02-21', 'MED900000102', '5142001102', 'bella.c@email.com', '202 Beech Rd', 'H7A1B2'),
('900000103', 'Caleb', 'Tran', '2009-09-09', 'MED900000103', '5142001103', 'caleb.t@email.com', '203 Birch Ave', 'H9P3H6'),
('900000104', 'Diana', 'Singh', '2008-12-03', 'MED900000104', '5142001104', 'diana.s@email.com', '204 Cedar St', 'J4V2H1'),
('900000105', 'Ethan', 'Kim', '2007-03-11', 'MED900000105', '5142001105', 'ethan.k@email.com', '205 Maple Ct', 'G1R1S8'),
('900000106', 'Fiona', 'Nguyen', '2012-08-24', 'MED900000106', '5142001106', 'fiona.n@email.com', '206 Oak Cir', 'J1H1R5'),
('900000107', 'Gabriel', 'Zhou', '2006-10-19', 'MED900000107', '5142001107', 'gabriel.z@email.com', '207 Pine Trl', 'G9A2B3'),
('900000108', 'Hannah', 'Martinez', '2007-07-14', 'MED900000108', '5142001108', 'hannah.m@email.com', '208 Poplar Pl', 'J8X3Y2'),
('900000109', 'Ian', 'Lopez', '2009-04-28', 'MED900000109', '5142001109', 'ian.l@email.com', '209 Redwood Blvd', 'J4W2G6'),
('900000110', 'Jade', 'Brown', '2011-11-02', 'MED900000110', '5142001110', 'jade.b@email.com', '210 Spruce St', 'J2B5R9'),
('900000111', 'Kylie', 'Taylor', '2010-01-17', 'MED900000111', '5142001111', 'kylie.t@email.com', '211 Sycamore Ln', 'H3G1P4'),
('900000112', 'Liam', 'White', '2006-06-06', 'MED900000112', '5142001112', 'liam.w@email.com', '212 Chestnut Dr', 'H3G1Z7'),
('900000113', 'Maya', 'Hall', '2012-03-30', 'MED900000113', '5142001113', 'maya.h@email.com', '213 Elm Grove', 'H3G1P1'),
('900000114', 'Noah', 'Bennett', '2007-05-25', 'MED900000114', '5142001114', 'noah.b@email.com', '214 Fir Row', 'H1A1A1'),
('900000115', 'Olivia', 'Diaz', '2008-09-09', 'MED900000115', '5142001115', 'olivia.d@email.com', '215 Willow Cres', 'H7A1B2'),
('900000116', 'Parker', 'Young', '2010-10-10', 'MED900000116', '5142001116', 'parker.y@email.com', '216 Hemlock Way', 'H9P3H6'),
('900000117', 'Quincy', 'Walker', '2011-01-01', 'MED900000117', '5142001117', 'quincy.w@email.com', '217 Magnolia Dr', 'J4V2H1'),
('900000118', 'Riley', 'Chowdhury', '2006-04-04', 'MED900000118', '5142001118', 'riley.c@email.com', '218 Olive Blvd', 'G1R1S8'),
('900000119', 'Sophia', 'Diaz', '2009-12-12', 'MED900000119', '5142001119', 'sophia.d@email.com', '219 Cedar Walk', 'J1H1R5'),
('900000120', 'Tyler', 'Garcia', '2007-08-08', 'MED900000120', '5142001120', 'tyler.g@email.com', '220 Birchwood Ln', 'G9A2B3'),
('900000201', 'Ursula', 'King', '1985-06-06', 'MED900000201', '5143001201', 'ursula.k@email.com', '301 Larch Dr', 'J8X3Y2'),
('900000202', 'Victor', 'Fox', '1980-01-23', 'MED900000202', '5143001202', 'victor.f@email.com', '302 Holly Cir', 'H3G1P4'),
('900000203', 'Wanda', 'Moore', '1983-10-15', 'MED900000203', '5143001203', 'wanda.m@email.com', '303 Willow Ln', 'J2B5R9'),
('900000204', 'Xena', 'Bryant', '1982-02-02', 'MED900000204', '5143001204', 'xena.b@email.com', '304 Aspen Ct', 'J1H1R5'),
('900000205', 'Yosef', 'Bell', '1987-11-11', 'MED900000205', '5143001205', 'yosef.b@email.com', '305 Cedar Grove', 'H7A1B2'),
('900000206', 'Zara', 'Ng', '1990-08-08', 'MED900000206', '5143001206', 'zara.n@email.com', '306 Elm Row', 'G1R1S8'),
('900000207', 'Abby', 'Chung', '1981-05-17', 'MED900000207', '5143001207', 'abby.c@email.com', '307 Maple Dr', 'J4V2H1'),
('900000208', 'Ben', 'Ahmed', '1979-12-30', 'MED900000208', '5143001208', 'ben.a@email.com', '308 Birch Lane', 'H1A1A1'),
('900000209', 'Cara', 'Yoon', '1986-03-12', 'MED900000209', '5143001209', 'cara.y@email.com', '309 Chestnut Blvd', 'H9P3H6'),
('900000210', 'Derek', 'Ibrahim', '1984-04-04', 'MED900000210', '5143001210', 'derek.i@email.com', '310 Spruce View', 'G9A2B3'),
('900000021', 'Una', 'Garcia', '1992-02-02', 'MED900000021', '5141001021', 'una.g@email.com', '121 Willow Way', 'H3G1P1'),
('900000022', 'Victor', 'Tran', '1991-07-15', 'MED900000022', '5141001022', 'victor.t@email.com', '122 Birch Hollow', 'H3G1Z7'),
('900000023', 'Wendy', 'Lam', '1993-04-09', 'MED900000023', '5141001023', 'wendy.l@email.com', '123 Oak Ridge', 'H3G1P4'),
('900000024', 'Xavier', 'Dube', '1990-10-30', 'MED900000024', '5141001024', 'xavier.d@email.com', '124 Maple Crest', 'J4V2H1'),
('900000025', 'Yara', 'Nguyen', '1995-12-18', 'MED900000025', '5141001025', 'yara.n@email.com', '125 Poplar Park', 'H1A1B1');



INSERT INTO Personnel (sin)
VALUES
('900000201'),  -- Ursula King
('900000202'),  -- Victor Fox
('900000203'),  -- Wanda Moore
('900000204'),  -- Xena Bryant
('900000205'),  -- Yosef Bell
('900000206'),  -- Zara Ng
('900000207'),  -- Abby Chung
('900000208'),  -- Ben Ahmed
('900000209'),  -- Cara Yoon
('900000210');  -- Derek Ibrahim


INSERT INTO Location (type, name, address, postal_code, phone_number, web_address, max_capacity, general_manager_id)
VALUES
('Head', 'Montreal Main', '123 Main Street', 'H1A1A1', '5141234567', 'www.montrealmain.com', 500, 1),
('Branch', 'Laval Branch', '456 Laval Street', 'H7A1B2', '4501234567', 'www.lavalbranch.com', 200, 2),
('Branch', 'Dorval Branch', '789 Dorval Avenue', 'H9P3H6', '5147894561', 'www.dorvalbranch.com', 150, 3),
('Branch', 'Longueuil Branch', '321 Longueuil Blvd', 'J4V2H1', '4509876543', 'www.longueuilbranch.com', 300, 4),
('Branch', 'Quebec City Branch', '987 Rue Saint-Jean', 'G1R1S8', '4181237890', 'www.quebeccitybranch.com', 250, 5),
('Branch', 'Sherbrooke Branch', '654 King Street', 'J1H1R5', '8194567890', 'www.sherbrookebranch.com', 180, 6),
('Branch', 'Trois-Rivières Branch', '852 Mauricie Road', 'G9A2B3', '8196543210', 'www.troisrivieresbranch.com', 220, 7),
('Branch', 'Gatineau Branch', '741 Hull Street', 'J8X3Y2', '8197891234', 'www.gatineaubranch.com', 270, 8),
('Branch', 'Brossard Branch', '159 Brossard Blvd', 'J4W2G6', '4503219876', 'www.brossardbranch.com', 300, 9),
('Branch', 'Drummondville Branch', '357 Centre-Ville Avenue', 'J2B5R9', '8198765432', 'www.drummondvillebranch.com', 160, 10);


INSERT INTO OperatesAt (personnel_id, location_id, start_date, end_date, role, mandate)
VALUES
((SELECT personnel_id FROM Personnel WHERE sin = '900000201'), 1, '2020-01-01', NULL, 'Coach', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000202'), 2, '2021-06-15', NULL, 'Assistant Coach', 'Volunteer'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000203'), 3, '2022-03-01', NULL, 'Treasurer', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000204'), 1, '2019-05-10', NULL, 'General Manager', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000205'), 2, '2021-07-01', NULL, 'Deputy Manager', 'Volunteer'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000206'), 3, '2020-10-15', '2022-10-15', 'Administrator', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000206'), 4, '2022-11-01', NULL, 'Administrator', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000207'), 4, '2021-08-10', NULL, 'Coach', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000208'), 5, '2022-02-20', NULL, 'Secretary', 'Volunteer'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000209'), 5, '2021-03-14', NULL, 'Other', 'Salaried'),
((SELECT personnel_id FROM Personnel WHERE sin = '900000210'), 6, '2021-04-01', NULL, 'Captain', 'Salaried');



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
('900000101', 1),
('900000102', 2),
('900000103', 3),
('900000104', 4),
('900000105', 5),
('900000106', 6),
('900000107', 7),
('900000108', 8),
('900000109', 9),
('900000110', 10);


INSERT INTO ClubMember (sin, height, weight, status, current_location_id, join_date, deactivation_date, last_location_id, last_role)
VALUES
-- Active youth (ages 11–18)
('900000101', 160, 50, 'Active', 1, '2023-05-01', NULL, NULL, NULL),
('900000102', 158, 48, 'Active', 2, '2022-06-01', NULL, NULL, NULL),
('900000103', 162, 55, 'Active', 3, '2024-01-01', NULL, NULL, NULL),
('900000104', 165, 53, 'Active', 4, '2023-03-01', NULL, NULL, NULL),
('900000105', 167, 57, 'Active', 5, '2024-02-15', NULL, NULL, NULL),
('900000106', 159, 49, 'Active', 6, '2023-04-22', NULL, NULL, NULL),
('900000107', 170, 60, 'Active', 7, '2024-05-12', NULL, NULL, NULL),
('900000108', 166, 52, 'Active', 8, '2022-08-08', NULL, NULL, NULL),
('900000109', 164, 50, 'Active', 9, '2023-07-01', NULL, NULL, NULL),
('900000110', 161, 47, 'Active', 10, '2022-10-10', NULL, NULL, NULL),
('900000111', 168, 59, 'Active', 1, '2023-01-01', NULL, NULL, NULL),
('900000112', 172, 62, 'Active', 2, '2024-03-03', NULL, NULL, NULL),
('900000113', 163, 54, 'Active', 3, '2022-09-09', NULL, NULL, NULL),
('900000114', 160, 50, 'Active', 4, '2024-04-04', NULL, NULL, NULL),
('900000115', 162, 56, 'Active', 5, '2023-06-06', NULL, NULL, NULL),
('900000116', 165, 58, 'Active', 6, '2022-07-07', NULL, NULL, NULL),
('900000117', 159, 49, 'Active', 7, '2023-02-02', NULL, NULL, NULL),
('900000118', 166, 55, 'Active', 8, '2024-05-05', NULL, NULL, NULL),
('900000119', 170, 60, 'Active', 9, '2023-11-11', NULL, NULL, NULL),
('900000120', 167, 58, 'Active', 10, '2024-01-20', NULL, NULL, NULL),

-- Additional valid active adult members
('900000021', 168, 60, 'Active', 2, '2024-02-10', NULL, NULL, NULL),
('900000022', 175, 72, 'Active', 3, '2024-03-15', NULL, NULL, NULL),
('900000023', 165, 59, 'Active', 4, '2023-11-01', NULL, NULL, NULL),
('900000024', 178, 74, 'Active', 5, '2024-01-20', NULL, NULL, NULL),
('900000025', 160, 55, 'Active', 6, '2024-05-05', NULL, NULL, NULL),

-- Deactivated adults (10+ over 18)
('900000201', 175, 70, 'Inactive', 1, '2015-01-01', '2024-01-01', 1, 'Libero'),
('900000202', 180, 85, 'Inactive', 2, '2014-05-10', '2023-12-31', 2, 'Setter'),
('900000203', 170, 68, 'Inactive', 3, '2013-03-03', '2022-06-15', 3, 'Outside Hitter'),
('900000204', 165, 66, 'Inactive', 4, '2012-02-02', '2023-09-09', 4, 'Defensive Specialist'),
('900000205', 178, 80, 'Inactive', 5, '2011-10-10', '2023-11-11', 5, 'Opposite'),
('900000206', 172, 75, 'Inactive', 6, '2016-07-07', '2024-02-02', 6, 'Middle Blocker'),
('900000207', 169, 62, 'Inactive', 7, '2010-08-08', '2024-03-01', 7, 'Coach'),
('900000208', 185, 90, 'Inactive', 8, '2013-06-06', '2023-10-10', 8, 'Outside Hitter'),
('900000209', 168, 65, 'Inactive', 9, '2012-12-12', '2024-04-01', 9, 'Libero'),
('900000210', 177, 73, 'Inactive', 10, '2011-09-01', '2024-05-05', 10, 'Other');


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
('Montreal Titans', 1, 'Male', 1),
('Laval Lightning', 2, 'Female', 2),
('Dorval Hawks', 3, 'Male', 3),
('Longueuil Lynx', 4, 'Female', 4),
('Quebec Falcons', 5, 'Female', 5),
('Sherbrooke Storm', 6, 'Male', 6),
('Trois-Rivières Tigers', 7, 'Male', 7),
('Gatineau Gators', 8, 'Male', 8),
('Brossard Blizzards', 9, 'Female', 9),
('Drummondville Dragons', 10, 'Female', 10);


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
(10, 4, 'Outside Hitter'),
(2, 1, 'Opposite'),
(3, 1, 'Middle Blocker'),
(4, 1, 'Libero'),
(5, 1, 'Defensive Specialist'),
(6, 1, 'Serving Specialist'),
(3, 2, 'Setter'),
(4, 2, 'Middle Blocker'),
(5, 2, 'Libero'),
(6, 2, 'Defensive Specialist'),
(9, 2, 'Serving Specialist');

-- Club member 11 (needs all roles)
INSERT INTO TeamMember (team_id, club_member_id, role) VALUES
(1, 11, 'Setter'),
(2, 11, 'Outside Hitter'),
(3, 11, 'Opposite'),
(4, 11, 'Middle Blocker'),
(5, 11, 'Libero'),
(6, 11, 'Defensive Specialist'),
(7, 11, 'Serving Specialist');

-- Club member 12 (needs all roles)
INSERT INTO TeamMember (team_id, club_member_id, role) VALUES
(2, 12, 'Setter'),
(3, 12, 'Outside Hitter'),
(4, 12, 'Opposite'),
(5, 12, 'Middle Blocker'),
(6, 12, 'Libero'),
(7, 12, 'Defensive Specialist'),
(8, 12, 'Serving Specialist');

-- Club member 13 (needs all roles)
INSERT INTO TeamMember (team_id, club_member_id, role) VALUES
(3, 13, 'Setter'),
(4, 13, 'Outside Hitter'),
(5, 13, 'Opposite'),
(6, 13, 'Middle Blocker'),
(7, 13, 'Libero'),
(8, 13, 'Defensive Specialist'),
(9, 13, 'Serving Specialist');

-- Club member 14 (needs all roles)
INSERT INTO TeamMember (team_id, club_member_id, role) VALUES
(4, 14, 'Setter'),
(5, 14, 'Outside Hitter'),
(6, 14, 'Opposite'),
(7, 14, 'Middle Blocker'),
(8, 14, 'Libero'),
(9, 14, 'Defensive Specialist'),
(10, 14, 'Serving Specialist');

-- Club member 15 (needs all roles)
INSERT INTO TeamMember (team_id, club_member_id, role) VALUES
(5, 15, 'Setter'),
(6, 15, 'Outside Hitter'),
(7, 15, 'Opposite'),
(8, 15, 'Middle Blocker'),
(9, 15, 'Libero'),
(10, 15, 'Defensive Specialist'),
(1, 15, 'Serving Specialist');


INSERT INTO Session (type, date, start_time, location_id, team1_id, team2_id, score_team1, score_team2)
VALUES
('Training', '2025-03-20', '10:00:00', 1, 1, 2, NULL, NULL),
('Game', '2025-03-21', '14:00:00', 2, 3, 4, 21, 18),
('Training', '2025-03-22', '16:00:00', 3, 5, 6, NULL, NULL),
('Game', '2025-03-23', '18:00:00', 4, 7, 8, 25, 27),
('Game', '2025-03-24', '12:00:00', 5, 9, 10, 30, 28),
('Training', '2025-03-25', '09:00:00', 6, 1, 3, NULL, NULL),
('Game', '2025-03-26', '17:00:00', 7, 2, 4, 19, 25),
('Game', '2025-03-22', '15:00:00', 2, 2, 3, 20, 15),
('Game', '2025-03-24', '15:00:00', 4, 4, 5, 21, 16),
('Game', '2025-03-25', '15:00:00', 5, 6, 7, 22, 17),
('Game', '2025-04-01', '14:00:00', 1, 1, 10, 27, 22);



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


INSERT INTO EmailLog (recipient_email, sender, email_date, subject, mail_body, email_type)
VALUES
('emma.williams@email.com', 'admin@myvc.com', '2025-03-20 09:00:00', 'Montreal Titans - 2025-03-22 17:00 - Training Session', 'Hello Emma Williams,
On 2025-03-22 at 17:00, you will be playing as Outside Hitter in a training session, at the following address: 123 Sports Ave, Montreal, QC.

Please refer to your captain John Smith at john.smith@email.com should any issue arise.

Thank you!', 'formation'),

('bob.s@email.com', 'admin@myvc.com', '2025-03-24 08:45:00', 'Deactivation on 2025-03-24', 'Hello Bob Smith,
Your club membership will be deactivated today, as you are now at the age of 18.

Congratulations!', 'deactivation'),
('grace.m@email.com', 'coach@myvc.com', '2025-03-25 11:00:00', 'Game Schedule Update', 'Your game has been rescheduled to March 26.', 'notification'),
('liam.brown@email.com', 'admin@myvc.com', '2025-03-19 10:30:00', 'Laval Lightning - 2025-03-21 18:30 - Regular Season Game', 'Hello Liam Brown,
On 2025-03-21 at 18:30, you will be playing as Setter in a regular season game, at the following address: 456 Volleyball Court, Laval, QC.

Please refer to your captain Sarah Johnson at sarah.j@email.com should any issue arise.

Thank you!', 'formation'),
('ava.brown@email.com', 'admin@myvc.com', '2025-03-20 10:30:00', 'Laval Lightning - 2025-03-21 18:30 - Regular Season Game', 'Hello Ava Brown,
On 2025-03-21 at 18:30, you will be playing as Outside Hitter in a regular season game, at the following address: 456 Volleyball Court, Laval, QC.

Please refer to your captain Sarah Johnson at sarah.j@email.com should any issue arise.

Thank you!', 'formation'),
('noah.williams@email.com', 'admin@myvc.com', '2025-03-18 14:00:00', 'Payment Reminder', 'Your next membership installment is due March 30.', 'notification'),
('charlotte.davis@email.com', 'admin@myvc.com', '2025-03-22 09:00:00', 'Deactivation on 2025-03-22', 'Hello Charlotte Davis,
Your club membership will be deactivated today, as you are now at the age of 18.

Congratulations!', 'deactivation'),
('sophia.miller@email.com', 'admin@myvc.com', '2025-03-23 11:45:00', 'Dorval Hawks - 2025-03-25 19:00 - Practice Session', 'Hello Sophia Miller,
On 2025-03-25 at 19:00, you will be playing as Middle Blocker in a practice session, at the following address: 789 Gymnasium Rd, Dorval, QC.

Please refer to your captain Michael Chen at michael.c@email.com should any issue arise.

Thank you!', 'formation'),
('lucas.johnson@email.com', 'coach@myvc.com', '2025-03-25 15:00:00', 'Practice Reminder', 'Practice starts at 6 PM sharp.', 'notification'),
('ethan.brown@email.com', 'admin@myvc.com', '2025-03-26 08:15:00', 'Eligibility Warning', 'You will be overage next season.', 'notification');