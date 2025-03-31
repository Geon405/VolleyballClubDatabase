USE lqc353_4;

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS OperatesAt;
DROP TABLE IF EXISTS Personnel;
DROP TABLE IF EXISTS FamilyMembers;
DROP TABLE IF EXISTS ClubMembers;
DROP TABLE IF EXISTS Payments;
DROP TABLE IF EXISTS Locations;

SET FOREIGN_KEY_CHECKS = 1;

-- Create Locations Table
CREATE TABLE Locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Head', 'Branch') NOT NULL,
    name VARCHAR(100),
    address VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code CHAR(7),
    phone_number CHAR(10),
    web_address VARCHAR(100),
    max_capacity INT
);

-- Create Personnel Table
CREATE TABLE Personnel (
    personnel_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    ssn CHAR(9) UNIQUE NOT NULL,
    medicare_number VARCHAR(15) UNIQUE NOT NULL, 
    phone_number CHAR(10),
    address VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code CHAR(6),
    email VARCHAR(100),
    role ENUM(
        'General Manager', 'Deputy Manager', 'Treasurer', 'Secretary', -- roles for head location only
        'Administrator', 'Captain', 'Coach', 'Assistant Coach', 'Other' -- general roles
    ),
    mandate ENUM('Volunteer', 'Salaried')
);

-- Create OperatesAt Table (To Track Work History)
CREATE TABLE OperatesAt (
    operates_at_id INT AUTO_INCREMENT PRIMARY KEY,
    personnel_id INT,
    location_id INT,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,  -- NULL means currently active
    FOREIGN KEY (personnel_id) REFERENCES Personnel(personnel_id),
    FOREIGN KEY (location_id) REFERENCES Locations(location_id)
);

-- Create FamilyMembers Table
CREATE TABLE FamilyMembers (
    family_member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    ssn CHAR(9) UNIQUE NOT NULL,
    medicare_number VARCHAR(15) UNIQUE NOT NULL,
    phone_number CHAR(10),
    address VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code CHAR(6),
    email VARCHAR(100),
    location_id INT,
    FOREIGN KEY (location_id) REFERENCES Locations(location_id)
);

-- Create ClubMembers Table
CREATE TABLE ClubMembers (
    club_member_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    height INT,
    weight INT,
    ssn CHAR(9) UNIQUE NOT NULL,
    medicare_number VARCHAR(15) UNIQUE NOT NULL,
    phone_number CHAR(10),
    address VARCHAR(100),
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code CHAR(6),
    family_member_id INT,
    location_id INT,
    FOREIGN KEY (family_member_id) REFERENCES FamilyMembers(family_member_id),
    FOREIGN KEY (location_id) REFERENCES Locations(location_id),
    relation ENUM('Father', 'Mother', 'Grandfather', 'Grandmother', 'Tutor', 'Partner', 'Friend', 'Brother', 'Sister', 'Other')
);

-- Create Payments Table
CREATE TABLE Payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    club_member_id INT,
    payment_date DATE,
    amount DECIMAL(10, 2),
    payment_year INT,
    installment_number TINYINT CHECK (installment_number BETWEEN 1 AND 4),
    method ENUM('Cash', 'Debit Card', 'Credit Card'),
    FOREIGN KEY (club_member_id) REFERENCES ClubMembers(club_member_id)
);
