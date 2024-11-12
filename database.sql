CREATE DATABASE blood_bank;
USE blood_bank;

CREATE TABLE donors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    blood_type VARCHAR(5) NOT NULL,
    donation_date DATETIME NOT NULL
);

CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    blood_type VARCHAR(5) NOT NULL,
    units_needed INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    request_date DATETIME NOT NULL
);

CREATE TABLE inventory (
    blood_type VARCHAR(5) PRIMARY KEY,
    units INT DEFAULT 0
);

INSERT INTO inventory (blood_type, units) VALUES
('A+', 0), ('A-', 0), ('B+', 0), ('B-', 0),
('AB+', 0), ('AB-', 0), ('O+', 0), ('O-', 0);