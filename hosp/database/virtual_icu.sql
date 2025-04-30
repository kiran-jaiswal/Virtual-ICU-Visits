CREATE DATABASE virtual_icu;
USE virtual_icu;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_type ENUM('family', 'staff', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100) NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    admission_date DATE NOT NULL,
    status ENUM('active', 'discharged') DEFAULT 'active',
    notes TEXT
);

CREATE TABLE visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT,
    visitor_id INT,
    visit_date DATETIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled') DEFAULT 'scheduled',
    meeting_link VARCHAR(255),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (visitor_id) REFERENCES users(id)
);
CREATE TABLE `daily_updates` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
INSERT INTO `daily_updates` (user_id, content) VALUES
(1, 'Patient is stable and recovering well.'),
(1, 'New medication prescribed.'),
(2, 'Patient had a good night\'s sleep.');

CREATE TABLE `messages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `sender` VARCHAR(100) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);
INSERT INTO `messages` (user_id, sender, content) VALUES
(1, 'Doctor Smith', 'Patient is responding well to treatment.'),
(1, 'Nurse Jane', 'Vitals are stable.'),
(2, 'Doctor Lee', 'Patient is scheduled for a check-up tomorrow.');