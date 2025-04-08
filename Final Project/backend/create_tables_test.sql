USE lqc353_4;

SET FOREIGN_KEY_CHECKS = 0;

-- Drop in reverse dependency order
DROP TABLE IF EXISTS EmailLog;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS Session;
DROP TABLE IF EXISTS TeamMember;
DROP TABLE IF EXISTS Team;
DROP TABLE IF EXISTS ClubMemberSecondaryRelationship;
DROP TABLE IF EXISTS ClubMemberFamily;
DROP TABLE IF EXISTS ClubMember;
DROP TABLE IF EXISTS SecondaryFamilyMember;
DROP TABLE IF EXISTS FamilyMember;
DROP TABLE IF EXISTS OperatesAt;
DROP TABLE IF EXISTS Personnel;
DROP TABLE IF EXISTS Person;
DROP TABLE IF EXISTS PostalCode;
DROP TABLE IF EXISTS Location;

-- PostalCode
CREATE TABLE PostalCode (
    postal_code CHAR(7) PRIMARY KEY,
    city VARCHAR(100),
    province VARCHAR(100)
);

-- Person
CREATE TABLE Person (
    sin CHAR(9) PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    birth_date DATE,
    medicare_card_number VARCHAR(15) UNIQUE,
    telephone_number CHAR(10),
    email_address VARCHAR(100),
    address VARCHAR(100),
    postal_code CHAR(7),
    FOREIGN KEY (postal_code) REFERENCES PostalCode(postal_code)
);


CREATE TABLE Personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    sin CHAR(9) UNIQUE,
    FOREIGN KEY (sin) REFERENCES Person(sin)
);

-- Location
CREATE TABLE Location (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    type ENUM('Head', 'Branch') NOT NULL,
    address VARCHAR(100),
    postal_code CHAR(7),
    phone_number CHAR(10),
    web_address VARCHAR(100),
    max_capacity INT,
    general_manager_id INT,
    FOREIGN KEY (postal_code) REFERENCES PostalCode(postal_code),
    FOREIGN KEY (general_manager_id) REFERENCES Personnel(personnel_id) ON DELETE CASCADE
);



-- OperatesAt
CREATE TABLE OperatesAt (
    operates_at_id INT AUTO_INCREMENT PRIMARY KEY,
    personnel_id INT,
    location_id INT,
    start_date DATE NOT NULL,
    end_date DATE,
    role VARCHAR(50),
    mandate ENUM('Volunteer', 'Salaried'),
    FOREIGN KEY (personnel_id) REFERENCES Personnel(personnel_id) ON DELETE SET NULL,
    FOREIGN KEY (location_id) REFERENCES Location(location_id) ON DELETE SET NULL
);

-- SecondaryFamilyMember
CREATE TABLE SecondaryFamilyMember (
    secondary_family_member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    phone_number CHAR(10),
    email_address VARCHAR(100)
);

-- FamilyMember
CREATE TABLE FamilyMember (
    family_member_id INT AUTO_INCREMENT PRIMARY KEY,
    sin CHAR(9) UNIQUE,
    secondary_family_member_id INT,
    FOREIGN KEY (sin) REFERENCES Person(sin),
    FOREIGN KEY (secondary_family_member_id) REFERENCES SecondaryFamilyMember(secondary_family_member_id) ON DELETE SET NULL
);

-- ClubMember
CREATE TABLE ClubMember (
    club_member_id INT AUTO_INCREMENT PRIMARY KEY,
    sin CHAR(9) UNIQUE,
    height INT,
    weight INT,
    status ENUM('Active', 'Inactive'),
    current_location_id INT,
    deactivation_date DATE,
    last_location_id INT,
    last_role VARCHAR(50),
    FOREIGN KEY (sin) REFERENCES Person(sin),
    FOREIGN KEY (current_location_id) REFERENCES Location(location_id) ON DELETE SET NULL,
    FOREIGN KEY (last_location_id) REFERENCES Location(location_id) ON DELETE SET NULL
);

-- ClubMemberFamily
CREATE TABLE ClubMemberFamily (
    club_member_id INT,
    family_member_id INT,
    relationship_type ENUM('Father', 'Mother', 'Grandfather', 'Grandmother', 'Uncle', 'Aunt', 'Tutor', 'Partner', 'Friend', 'Other'),
    PRIMARY KEY (club_member_id, family_member_id),
    FOREIGN KEY (club_member_id) REFERENCES ClubMember(club_member_id) ON DELETE CASCADE,
    FOREIGN KEY (family_member_id) REFERENCES FamilyMember(family_member_id) ON DELETE CASCADE
);

-- ClubMemberSecondaryRelationship
CREATE TABLE ClubMemberSecondaryRelationship (
    club_member_id INT,
    secondary_family_member_id INT,
    relationship_type ENUM('Father', 'Mother', 'Grandfather', 'Grandmother', 'Uncle', 'Aunt', 'Tutor', 'Partner', 'Friend', 'Other'),
    PRIMARY KEY (club_member_id, secondary_family_member_id),
    FOREIGN KEY (club_member_id) REFERENCES ClubMember(club_member_id) ON DELETE CASCADE,
    FOREIGN KEY (secondary_family_member_id) REFERENCES SecondaryFamilyMember(secondary_family_member_id) ON DELETE CASCADE
);

-- Team
CREATE TABLE Team (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    location_id INT,
    captain_id INT,
    gender ENUM('Male', 'Female', 'Coed'),
    FOREIGN KEY (location_id) REFERENCES Location(location_id) ON DELETE SET NULL,
    FOREIGN KEY (captain_id) REFERENCES FamilyMember(family_member_id) ON DELETE CASCADE
);

-- TeamMember
CREATE TABLE TeamMember (
    team_id INT,
    club_member_id INT,
    role VARCHAR(50),
    PRIMARY KEY (team_id, club_member_id),
    FOREIGN KEY (team_id) REFERENCES Team(team_id) ON DELETE CASCADE,
    FOREIGN KEY (club_member_id) REFERENCES ClubMember(club_member_id) ON DELETE CASCADE
);

-- Session (address removed)
CREATE TABLE Session (
    session_id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Training', 'Game') NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    location_id INT,
    team1_id INT,
    team2_id INT,
    score_team1 INT,
    score_team2 INT,
    FOREIGN KEY (location_id) REFERENCES Location(location_id) ON DELETE SET NULL,
    FOREIGN KEY (team1_id) REFERENCES Team(team_id) ON DELETE SET NULL,
    FOREIGN KEY (team2_id) REFERENCES Team(team_id) ON DELETE SET NULL
);

-- Payments
CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    club_member_id INT,
    payment_date DATE,
    amount DECIMAL(10, 2),
    payment_year INT,
    installment_number TINYINT CHECK (installment_number BETWEEN 1 AND 4),
    payment_method ENUM('Cash', 'Debit Card', 'Credit Card'),
    FOREIGN KEY (club_member_id) REFERENCES ClubMember(club_member_id) ON DELETE CASCADE
);

-- EmailLog
CREATE TABLE EmailLog (
    email_id INT AUTO_INCREMENT PRIMARY KEY,
    recipient_email VARCHAR(100),
    sender VARCHAR(100),
    email_date DATETIME,
    subject VARCHAR(255),
    mail_body TEXT,
    email_type ENUM('formation', 'deactivation', 'notification')
);
