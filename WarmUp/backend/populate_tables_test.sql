USE lqc353_4;

-- Disable foreign key checks for safe data population
SET FOREIGN_KEY_CHECKS = 0;

-- Clear existing data
TRUNCATE TABLE Locations;
TRUNCATE TABLE Personnel;
TRUNCATE TABLE OperatesAt;
TRUNCATE TABLE FamilyMembers;
TRUNCATE TABLE ClubMembers;
TRUNCATE TABLE Payments;

SET FOREIGN_KEY_CHECKS = 1;

-- Populate Locations Table (11 locations)
INSERT INTO Locations (type, name, address, city, province, postal_code, phone_number, web_address, max_capacity)
VALUES
    ('Head', 'Montreal Main', '123 Main Street', 'Montreal', 'QC', 'H1A1A1', '5141234567', 'www.montrealmain.com', 500),
    ('Branch', 'Laval Branch', '456 Laval Street', 'Laval', 'QC', 'H7A1B2', '4501234567', 'www.lavalbranch.com', 200),
    ('Branch', 'Dorval Branch', '789 Dorval Avenue', 'Dorval', 'QC', 'H9P3H6', '5147894561', 'www.dorvalbranch.com', 150),
    ('Branch', 'Longueuil Branch', '321 Longueuil Blvd', 'Longueuil', 'QC', 'J4V2H1', '4509876543', 'www.longueuilbranch.com', 300),
    ('Branch', 'Quebec City Branch', '987 Rue Saint-Jean', 'Quebec City', 'QC', 'G1R1S8', '4181237890', 'www.quebeccitybranch.com', 250),
    ('Branch', 'Sherbrooke Branch', '654 King Street', 'Sherbrooke', 'QC', 'J1H1R5', '8194567890', 'www.sherbrookebranch.com', 180),
    ('Branch', 'Trois-Rivières Branch', '852 Mauricie Road', 'Trois-Rivières', 'QC', 'G9A2B3', '8196543210', 'www.troisrivieresbranch.com', 220),
    ('Branch', 'Gatineau Branch', '741 Hull Street', 'Gatineau', 'QC', 'J8X3Y2', '8197891234', 'www.gatineaubranch.com', 270),
    ('Branch', 'Brossard Branch', '159 Brossard Blvd', 'Brossard', 'QC', 'J4W2G6', '4503219876', 'www.brossardbranch.com', 300),
    ('Branch', 'Drummondville Branch', '357 Centre-Ville Avenue', 'Drummondville', 'QC', 'J2B5R9', '8198765432', 'www.drummondvillebranch.com', 160),
    ('Branch', 'Chicoutimi Branch', '963 Saguenay Street', 'Chicoutimi', 'QC', 'G7H3A4', '4185678901', 'www.chicoutimibranch.com', 200);

-- Populate Personnel Table (12 personnel) Olivia Davis and Henry Johnson also being family members
INSERT INTO Personnel (first_name, last_name, date_of_birth, ssn, medicare_number, phone_number, address, city, province, postal_code, email, role, mandate)
VALUES
    ('John', 'Doe', '1980-06-15', '123450789', 'MED123456789', '5141112222', '123 Maple Street', 'Montreal', 'QC', 'H1A1B1', 'johndoe@email.com', 'General Manager', 'Salaried'),
    ('Jane', 'Smith', '1985-04-20', '223344555', 'MED223344555', '5143334444', '456 Oak Street', 'Laval', 'QC', 'H7A1C2', 'janesmith@email.com', 'Coach', 'Volunteer'),
    ('Robert', 'Johnson', '1990-02-10', '334455668', 'MED334465668', '5145556666', '789 Cedar Avenue', 'Dorval', 'QC', 'H9S2X3', 'robertjohnson@email.com', 'Administrator', 'Salaried'),
    ('Alice', 'Brown', '1995-11-05', '445566770', 'MED445566770', '5147778888', '321 Birch Street', 'Montreal', 'QC', 'H1C2B3', 'alicebrown@email.com', 'Deputy Manager', 'Volunteer'),
    ('Emily', 'Davis', '1983-07-19', '556677881', 'MED556677881', '5148889999', '321 Spruce Avenue', 'Longueuil', 'QC', 'J4V1W2', 'emilydavis@email.com', 'Coach', 'Salaried'),
    ('Michael', 'Clark', '1975-03-22', '667788992', 'MED667788992', '5142223333', '789 Birch Street', 'Laval', 'QC', 'H7A2D3', 'michaelclark@email.com', 'Assistant Coach', 'Volunteer'),
    ('Olivia', 'Martinez', '1987-10-05', '778899051', 'MED778899051', '5146543210', '555 Willow Lane', 'Dorval', 'QC', 'H9H3L2', 'oliviamartinez@email.com', 'Coach', 'Salaried'),
    ('Daniel', 'Evans', '1982-12-18', '889900112', 'MED889900112', '5144445555', '234 Elm Street', 'Montreal', 'QC', 'H1A2D4', 'danielevans@email.com', 'Other', 'Salaried'),
    ('Mia', 'Roberts', '1991-08-29', '990011223', 'MED990011223', '5147776666', '678 Poplar Drive', 'Longueuil', 'QC', 'J4V2Y1', 'miaroberts@email.com', 'Administrator', 'Volunteer'),
    ('James', 'Wilson', '1986-04-15', '112233485', 'MED112233485', '5148887777', '432 Birchwood Ave', 'Laval', 'QC', 'H7B3K5', 'jameswilson@email.com', 'Other', 'Salaried'),
    ('Henry', 'Johnson', '1978-08-22', '123456787', 'MED123456787', '5145554444', '987 Cedar Ave', 'Montreal', 'QC', 'H3G1P4', 'henryjohnson@email.com', 'Other', 'Salaried'),
    ('Olivia', 'Davis', '1980-07-15', '223344556', 'MED223344556', '5146667777', '654 Birch St', 'Laval', 'QC', 'H7G1J3', 'oliviadavis@email.com', 'Other', 'Salaried'),
    ('Chris', 'Bing', '1985-12-12', '926153786', 'MED926153786', '5142154414', '523 Hazel St', 'Montreal', 'QC', 'H3G1Z7', 'icecreamwastaken@email.com', 'Treasurer', 'Salaried'),
    ('Bully', 'Maguire', '1962-04-25', '993366445', 'MED993366445', '5142263527', '865 Juniper Drive', 'Montreal', 'QC', 'H3G1P1', 'spooderman@email.com', 'Secretary', 'Salaried');

-- Populate OperatesAt Table (Tracking Work History)
INSERT INTO OperatesAt (personnel_id, location_id, start_date, end_date)
VALUES
    (1, 1, '2020-01-01', NULL),  -- John Doe is still at Montreal Main
    (2, 2, '2021-06-15', NULL),  -- Jane Smith is at Laval
    (3, 3, '2019-09-20', '2022-12-31'),
    (3, 1, '2023-01-01', NULL),  -- Robert moved to Montreal
    (4, 1, '2022-05-01', NULL),
    (5, 4, '2023-01-10', NULL),
    (6, 2, '2021-09-01', '2022-08-31'),
    (6, 3, '2022-09-01', NULL),  -- Michael moved from Laval to Dorval
    (11, 1, '2023-12-12', NULL),  -- Henry Johnson as Personnel
    (12, 2, '2021-06-24', NULL),  -- Olivia Davis as Personnel
    (13, 1, '2012-02-02', NULL),
    (14, 1, '2016-08-12', NULL);

INSERT INTO FamilyMembers (first_name, last_name, date_of_birth, ssn, medicare_number, phone_number, address, city, province, postal_code, email, location_id)
VALUES
    ('Sophia', 'Williams', '1970-02-25', '112233445', 'MED112233445', '5141239876', '123 Main Road', 'Montreal', 'QC', 'H1A2B3', 'sophiawilliams@email.com', 1),
    ('James', 'Brown', '1965-05-10', '998877665', 'MED998877665', '5144567890', '456 Laval Blvd', 'Laval', 'QC', 'H7A4B5', 'jamesbrown@email.com', 2),
    ('Laura', 'White', '1972-11-10', '667788554', 'MED667788554', '5149995555', '789 Elm St', 'Dorval', 'QC', 'H9K3L1', 'laurawhite@email.com', 3),
    ('Henry', 'Johnson', '1978-08-22', '123456787', 'MED123456787', '5145554444', '987 Cedar Ave', 'Montreal', 'QC', 'H3G1P4', 'henryjohnson@email.com', 1),
    ('Olivia', 'Davis', '1980-07-15', '223344556', 'MED223344556', '5146667777', '654 Birch St', 'Laval', 'QC', 'H7G1J3', 'oliviadavis@email.com', 2),
    ('Michael', 'Martinez', '1969-03-10', '334455667', 'MED334455667', '5148886666', '543 Willow Ln', 'Dorval', 'QC', 'H9R2Y8', 'michaelmartinez@email.com', 3),
    ('Emma', 'Clark', '1975-11-30', '445566778', 'MED445566778', '5149993333', '222 Pine Rd', 'Longueuil', 'QC', 'J4G1Z9', 'emmaclark@email.com', 4),
    ('Daniel', 'Rodriguez', '1982-06-12', '556677889', 'MED556677889', '5142221111', '123 Spruce Ave', 'Montreal', 'QC', 'H1A3B4', 'danielrodriguez@email.com', 1),
    ('Sophia', 'Miller', '1973-09-25', '667788990', 'MED667788990', '5147778888', '555 Poplar St', 'Laval', 'QC', 'H7T2X3', 'sophiamiller@email.com', 2),
    ('Ethan', 'Garcia', '1985-04-18', '778899001', 'MED778899001', '5143332222', '777 Elmwood Dr', 'Dorval', 'QC', 'H9N4M5', 'ethangarcia@email.com', 3);

INSERT INTO ClubMembers (first_name, last_name, date_of_birth, height, weight, ssn, medicare_number, phone_number, address, city, province, postal_code, family_member_id, location_id, relation)
VALUES
    -- Sophia Williams (2 children)
    -- Emma Williams is an overager, won't be active
    ('Emma', 'Williams', '2000-11-15', 165, 55, '889900776', 'MED889900776', '5149998888', '123 Main Road', 'Montreal', 'QC', 'H1A2B3', 1, 1, 'Mother'),
    ('Noah', 'Williams', '2010-06-21', 158, 50, '990011112', 'MED990011112', '5149874563', '123 Main Road', 'Montreal', 'QC', 'H1A2B3', 1, 1, 'Father'),

    -- James Brown (3 children)
    ('Liam', 'Brown', '2010-03-22', 160, 52, '776655443', 'MED776655443', '4506667777', '456 Laval Blvd', 'Laval', 'QC', 'H7A4B5', 2, 2, 'Brother'),
    ('Ava', 'Brown', '2007-12-05', 170, 60, '112299338', 'MED112299338', '5143211234', '456 Laval Blvd', 'Laval', 'QC', 'H7A4B5', 2, 2, 'Sister'),
    ('Ethan', 'Brown', '2011-08-17', 154, 48, '334455668', 'MED334455668', '5146541237', '456 Laval Blvd', 'Laval', 'QC', 'H7A4B5', 2, 2, 'Mother'),

    -- Laura White (1 child)
    ('Sophia', 'Miller', '2009-06-30', 170, 60, '112299337', 'MED112299337', '5148889999', '789 Elm St', 'Dorval', 'QC', 'H9K3L1', 3, 3, 'Brother'),

    -- Henry Johnson (2 children)
    ('Lucas', 'Johnson', '2008-04-12', 168, 62, '556677112', 'MED556677112', '5147779999', '987 Cedar Ave', 'Montreal', 'QC', 'H3G1P4', 4, 1, 'Mother'),
    ('Mia', 'Johnson', '2010-10-10', 162, 58, '667788113', 'MED667788113', '5142224445', '987 Cedar Ave', 'Montreal', 'QC', 'H3G1P4', 4, 1, 'Father'),

    -- Olivia Davis (2 children)
    ('Daniel', 'Davis', '2009-02-18', 165, 54, '778899115', 'MED778899115', '5143456789', '654 Birch St', 'Laval', 'QC', 'H7G1J3', 5, 2, 'Brother'),
    ('Charlotte', 'Davis', '2011-06-08', 152, 49, '889900117', 'MED889900117', '5146547890', '654 Birch St', 'Laval', 'QC', 'H7G1J3', 5, 2, 'Sister'),

    -- Random single members
    ('Elijah', 'Wilson', '2007-03-12', 175, 66, '990011123', 'MED990011123', '5147654321', '444 Cedar Blvd', 'Dorval', 'QC', 'H9G2T3', 6, 3, 'Brother'),
    ('Harper', 'Harris', '2009-12-01', 162, 55, '112233335', 'MED112233335', '5142468101', '555 Spruce Ln', 'Longueuil', 'QC', 'J4N3K1', 7, 4, 'Sister');

-- Populate Payments Table (Adding 25 transactions for 2024)
INSERT INTO Payments (club_member_id, payment_date, amount, payment_year, installment_number, method)
VALUES
    -- Membership payments (non-donations) and donations by overpayments (210 in overpayments)
    (1, '2024-01-05', 120, 2024, 1, 'Credit Card'),
    (2, '2024-02-12', 50, 2024, 1, 'Cash'),
    (2, '2024-03-10', 70, 2024, 2, 'Cash'),
    (3, '2024-01-20', 100, 2024, 1, 'Debit Card'),
    (4, '2024-04-05', 100, 2024, 1, 'Credit Card'),
    (5, '2024-02-18', 50, 2024, 1, 'Debit Card'),
    (5, '2024-03-15', 50, 2024, 2, 'Debit Card'),
    (6, '2024-05-01', 100, 2024, 1, 'Credit Card'),
    (7, '2024-06-10', 200, 2024, 1, 'Cash'),
    (8, '2024-07-14', 160, 2024, 1, 'Credit Card'),
    (9, '2024-08-20', 100, 2024, 1, 'Debit Card'),
    (10, '2024-09-05', 50, 2024, 1, 'Cash'),
    (10, '2024-10-01', 50, 2024, 2, 'Cash'),
    (11, '2024-10-20', 10, 2024, 1, 'Credit Card'), -- Elijah Wilson will not have fees paid for the year so not active
    (12, '2024-11-10', 110, 2024, 1, 'Debit Card'),

    -- Donations (190 in donations, 400 counting overpayments from above)
    (1, '2024-01-20', 20, 2024, 2, 'Credit Card'),
    (2, '2024-02-28', 15, 2024, 3, 'Cash'),
    (3, '2024-03-10', 25, 2024, 2, 'Debit Card'),
    (5, '2024-04-15', 50, 2024, 3, 'Credit Card'),
    (6, '2024-05-20', 10, 2024, 2, 'Cash'),
    (7, '2024-06-25', 30, 2024, 2, 'Credit Card'),
    (9, '2024-08-15', 40, 2024, 2, 'Debit Card');

    -- might require new rows to add active status in 2025

    -- rows for payments in 2025
INSERT INTO Payments (club_member_id, payment_date, amount, payment_year, installment_number, method)
VALUES
    (1, '2025-03-12', 80, 2025, 1, 'Cash'),
    (1, '2025-12-24', 50, 2025, 2, 'Cash'),
    (3, '2025-09-06', 200, 2025, 1, 'Credit Card'),
    (3, '2025-03-15', 12, 2025, 2, 'Cash'),
    (4, '2025-05-03', 10, 2025, 1, 'Credit Card'),
    (4, '2025-10-02', 90, 2025, 2, 'Cash'),
    (5, '2024-06-22', 170, 2025, 1, 'Credit Card'),
    (6, '2025-04-13', 120, 2025, 1, 'Debit Card'),
    (7, '2025-02-27', 120, 2025, 1, 'Cash'),
    (8, '2025-11-30', 80, 2025, 1, 'Cash'),
    (8, '2025-11-22', 122, 2025, 2, 'Credit Card'),
    (9, '2025-02-13', 40, 2025, 1, 'Cash'),
    (10, '2025-06-13', 160, 2025, 1, 'Cash'),
    (11, '2025-12-13', 120, 2025, 1, 'Credit Card'), 
    (12, '2025-10-11', 10, 2025, 1, 'Debit Card'),
    (12, '2025-12-23', 20, 2025, 2, 'Debit Card');