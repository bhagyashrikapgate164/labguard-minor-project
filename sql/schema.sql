-- Schema for LabGuard Management System
CREATE DATABASE IF NOT EXISTS labguard CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE labguard;

-- Students
CREATE TABLE IF NOT EXISTS students (
	id INT AUTO_INCREMENT PRIMARY KEY,
	full_name VARCHAR(100) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Admins
CREATE TABLE IF NOT EXISTS admins (
	id INT AUTO_INCREMENT PRIMARY KEY,
	full_name VARCHAR(100) NOT NULL,
	password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Labs
CREATE TABLE IF NOT EXISTS labs (
	id INT AUTO_INCREMENT PRIMARY KEY,
	lab_name VARCHAR(120) NOT NULL,
	room VARCHAR(50) NULL
) ENGINE=InnoDB;

-- Faculty
CREATE TABLE IF NOT EXISTS faculty (
	id INT AUTO_INCREMENT PRIMARY KEY,
	full_name VARCHAR(100) NOT NULL,
	email VARCHAR(150) NULL
) ENGINE=InnoDB;

-- Problems
CREATE TABLE IF NOT EXISTS problems (
	id INT AUTO_INCREMENT PRIMARY KEY,
	student_id INT NOT NULL,
	lab_name VARCHAR(120) NOT NULL,
	equipment VARCHAR(120) NOT NULL,
	issue_type ENUM('Equipment Issue','Damage','Missing Item') NOT NULL,
	description TEXT NOT NULL,
	image_path VARCHAR(255) NULL,
	status ENUM('Pending','Verified','Solved') NOT NULL DEFAULT 'Pending',
	verified_by INT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	verified_at DATETIME NULL,
	solved_at DATETIME NULL,
	CONSTRAINT fk_problems_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
	CONSTRAINT fk_problems_admin FOREIGN KEY (verified_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed Students
INSERT INTO students (full_name) VALUES
	('Bhagyashri Kapgate'),
	('Aryan Bawane'),
	('Ayesha Yadav'),
	('Awadhi Rao'),
	('Bhavik Titarmare'),
	('Aryan Ojha');

-- Seed Admins (plaintext demo password: admin123)
INSERT INTO admins (full_name, password) VALUES
	('Hemlata Kosare', 'admin123'),
	('Pragati Futinge', 'admin123');

-- Seed Labs
INSERT INTO labs (lab_name, room) VALUES
	('Computer Lab A', 'A-101'),
	('Electronics Lab', 'B-203'),
	('Mechanical Workshop', 'C-002');

-- Seed Faculty
INSERT INTO faculty (full_name, email) VALUES
	('Dr. S. Sharma', 'sharma@example.edu'),
	('Prof. R. Mehta', 'mehta@example.edu'),
	('Ms. K. Iyer', 'iyer@example.edu');


-- CREATE TABLE users (
--   id INT AUTO_INCREMENT PRIMARY KEY,
--   username VARCHAR(50) NOT NULL,
--   fullname VARCHAR(100) DEFAULT NULL,
--   email VARCHAR(100) NOT NULL UNIQUE,
--   role VARCHAR(50) DEFAULT 'user',
--   phone_no VARCHAR(20) DEFAULT NULL,
--   password_hash VARCHAR(255) NOT NULL,
--   created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );
