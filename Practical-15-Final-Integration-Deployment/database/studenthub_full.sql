-- ==========================================================
-- StudentHub Portal - Complete Integrated Database Dump
-- CHARUSAT FTE ITUE203 - Web Development Frameworks
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `studenthub` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `studenthub`;

-- Drop existing tables in reverse dependency order
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Table: users
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'student') NOT NULL DEFAULT 'student',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_users_email` (`email`),
  INDEX `idx_users_role` (`role`)
) ENGINE=InnoDB;

-- 2. Table: students
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `roll_no` VARCHAR(20) NOT NULL UNIQUE,
  `department` VARCHAR(50) NOT NULL,
  `semester` INT NOT NULL DEFAULT 1,
  `cgpa` DECIMAL(3,2) DEFAULT 0.00,
  `contact_no` VARCHAR(15) NOT NULL,
  `address` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_students_roll` (`roll_no`),
  INDEX `idx_students_dept` (`department`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Table: events
CREATE TABLE `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `event_date` DATE NOT NULL,
  `venue` VARCHAR(100) NOT NULL,
  `max_seats` INT NOT NULL DEFAULT 50,
  `poster_image` VARCHAR(255) DEFAULT 'default_event.svg',
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_events_category` (`category`),
  INDEX `idx_events_date` (`event_date`),
  FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Table: event_registrations
CREATE TABLE `event_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('confirmed', 'cancelled') NOT NULL DEFAULT 'confirmed',
  UNIQUE KEY `unique_student_event` (`event_id`, `student_id`),
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================================
-- Initial Seed Data
-- Default Test Passwords:
-- Admin: Admin@123 ($2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e)
-- Student: Student@123 ($2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e)
-- ==========================================================

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`) VALUES
(1, 'System Administrator', 'admin@charusat.edu.in', '$2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e', 'admin', 'active'),
(2, 'Faculty Coordinator', 'faculty@charusat.edu.in', '$2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e', 'admin', 'active'),
(3, 'Aarav Sharma', 'aarav.it@charusat.edu.in', '$2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e', 'student', 'active'),
(4, 'Diya Patel', 'diya.ce@charusat.edu.in', '$2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e', 'student', 'active'),
(5, 'Rohan Mehta', 'rohan.it@charusat.edu.in', '$2y$10$wN31XbJg3s2jLwW4q3g3u.eH/jS7K9X2/p8wz7i3t4l1a2b3c4d5e', 'student', 'active');

INSERT INTO `students` (`id`, `user_id`, `roll_no`, `department`, `semester`, `cgpa`, `contact_no`, `address`) VALUES
(1, 3, '23IT001', 'Information Technology', 3, 8.75, '9876543210', 'Anand, Gujarat'),
(2, 4, '23CE014', 'Computer Engineering', 3, 9.10, '9876543211', 'Ahmedabad, Gujarat'),
(3, 5, '23IT035', 'Information Technology', 3, 7.80, '9876543212', 'Vadodara, Gujarat');

INSERT INTO `events` (`id`, `title`, `category`, `description`, `event_date`, `venue`, `max_seats`, `poster_image`, `created_by`) VALUES
(1, 'Hackathon 2026', 'Technical', '24-hour coding sprint solving campus problems with modern frameworks.', '2026-11-15', 'Lab 101, IT Dept', 120, 'default_event.svg', 1),
(2, 'Annual Cultural Fest - Spandan', 'Cultural', 'Showcase of music, dance, drama, and regional heritage across institutes.', '2026-12-05', 'University Auditorium', 500, 'default_event.svg', 1),
(3, 'Web Development Workshop', 'Workshop', 'Hands-on practical sessions building full stack web applications.', '2026-11-20', 'Seminar Hall A', 80, 'default_event.svg', 2),
(4, 'Inter-College Cricket Tournament', 'Sports', 'Annual 10-over cricket championship between engineering departments.', '2026-12-10', 'Sports Complex', 200, 'default_event.svg', 1);

INSERT INTO `event_registrations` (`id`, `event_id`, `student_id`, `status`) VALUES
(1, 1, 1, 'confirmed'),
(2, 3, 1, 'confirmed'),
(3, 1, 2, 'confirmed'),
(4, 2, 2, 'confirmed'),
(5, 3, 3, 'confirmed');
