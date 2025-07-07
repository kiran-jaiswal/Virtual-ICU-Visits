CREATE TABLE IF NOT EXISTS vitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    heart_rate INT,
    systolic INT,
    diastolic INT,
    spo2 INT,
    temperature DECIMAL(4,1),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

CREATE TABLE IF NOT EXISTS patient_notes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    staff_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (staff_id) REFERENCES users(id)
);
CREATE TABLE `family_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `patient_id` (`patient_id`),
  CONSTRAINT `family_members_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `family_members_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO family_members (user_id, patient_id, relationship) 
VALUES (1, 1, 'Parent'); -- Replace 1,1 with actual user_id and patient_id