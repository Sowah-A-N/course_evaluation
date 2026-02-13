-- phpMyAdmin SQL Dump
-- Refactored Course Evaluation System Database
-- Generation Time: February 13, 2026
-- Server version: 8.0.31
-- PHP Version: 8.1.13
--
-- MAJOR CHANGES FROM ORIGINAL:
-- 1. All tables converted to InnoDB
-- 2. Department relationships normalized (VARCHAR → INT)
-- 3. Pseudonymous evaluation system implemented
-- 4. Academic year UNIQUE constraint removed
-- 5. active_semester renamed to semesters with proper structure
-- 6. user_details primary key renamed to user_id
-- 7. New tables: evaluation_tokens, course_lecturers, audit_logs
-- 8. Proper indexing for performance

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `course_evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
-- CHANGES: Removed UNIQUE constraint on is_active, Changed to InnoDB
--

DROP TABLE IF EXISTS `academic_year`;
CREATE TABLE IF NOT EXISTS `academic_year` (
  `academic_year_id` int NOT NULL AUTO_INCREMENT,
  `start_year` int NOT NULL,
  `end_year` int GENERATED ALWAYS AS ((`start_year` + 1)) STORED,
  `year_label` varchar(9) GENERATED ALWAYS AS (concat(`start_year`, '/',`end_year`)) STORED,
  `is_active` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`academic_year_id`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_year`
--

INSERT INTO `academic_year` (`academic_year_id`, `start_year`, `is_active`) VALUES
(1, 2024, 1),
(2, 2023, 0);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
-- CHANGES: Renamed from active_semester, Added academic_year_id, Changed to InnoDB
--

DROP TABLE IF EXISTS `semesters`;
CREATE TABLE IF NOT EXISTS `semesters` (
  `semester_id` int NOT NULL AUTO_INCREMENT,
  `academic_year_id` int NOT NULL,
  `semester_name` enum('First','Second') NOT NULL,
  `semester_value` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`semester_id`),
  KEY `idx_academic_year` (`academic_year_id`),
  KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `academic_year_id`, `semester_name`, `semester_value`, `is_active`) VALUES
(1, 1, 'First', 1, 0),
(2, 1, 'Second', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `hod_id` int NOT NULL DEFAULT '0',
  `dep_name` varchar(100) NOT NULL,
  `dep_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `dep_code` (`dep_code`),
  KEY `idx_hod_id` (`hod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`t_id`, `hod_id`, `dep_name`, `dep_code`) VALUES
(1, 4, 'Department Of Transport', 'DOT'),
(2, 2, 'ICT', 'ICT'),
(3, 5, 'Marine Engineering Department', 'MEE'),
(4, 7, 'Electrical Department', 'EEE'),
(5, 20, 'test', 'TEE'),
(6, 0, 'GRADUATE SCHOOL', 'GRAD001');

-- --------------------------------------------------------

--
-- Table structure for table `level`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `level`;
CREATE TABLE IF NOT EXISTS `level` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `level_name` varchar(50) NOT NULL,
  `level_number` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `level_number` (`level_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`t_id`, `level_name`, `level_number`) VALUES
(1, 'level 100', 100),
(2, 'level 200', 200),
(3, 'level 300', 300),
(4, 'level 400', 400);

-- --------------------------------------------------------

--
-- Table structure for table `programme`
-- CHANGES: department VARCHAR changed to department_id INT, Changed to InnoDB
--

DROP TABLE IF EXISTS `programme`;
CREATE TABLE IF NOT EXISTS `programme` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `prog_code` varchar(20) NOT NULL,
  `prog_name` varchar(100) NOT NULL,
  `department_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `prog_code` (`prog_code`),
  KEY `idx_department` (`department_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programme`
--

INSERT INTO `programme` (`t_id`, `prog_code`, `prog_name`, `department_id`) VALUES
(2, 'BIT', 'BSc Information Technology', 2),
(3, 'BCS', 'BSc Computer Science', 2),
(4, 'MEE', 'Bsc Marine Engineering', 3);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
-- CHANGES: department VARCHAR changed to department_id INT, Changed to InnoDB
--

DROP TABLE IF EXISTS `classes`;
CREATE TABLE IF NOT EXISTS `classes` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(50) NOT NULL,
  `department_id` int NOT NULL,
  `year_of_completion` int NOT NULL,
  `programme_id` int NOT NULL,
  `level_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `class_name` (`class_name`),
  KEY `idx_department` (`department_id`),
  KEY `idx_programme` (`programme_id`),
  KEY `idx_level` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`t_id`, `class_name`, `department_id`, `year_of_completion`, `programme_id`, `level_id`) VALUES
(1, 'BIT28', 2, 2028, 2, 1),
(2, 'BIT27', 2, 2027, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
-- CHANGES: department VARCHAR changed to department_id INT, Changed to InnoDB
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int NOT NULL,
  `level_id` int NOT NULL,
  `semester_id` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_code` (`course_code`),
  KEY `idx_department` (`department_id`),
  KEY `idx_level` (`level_id`),
  KEY `idx_semester` (`semester_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course_code`, `name`, `department_id`, `level_id`, `semester_id`) VALUES
(1, 'BINT 108', 'Principles of Programming and Problem Solving', 2, 1, 1),
(2, 'BINT 309', 'DATABASES 1', 2, 2, 2),
(5, 'BINT 109', 'INTRO TO WEB DESIGN', 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `role_id` (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`t_id`, `role_id`, `role_name`) VALUES
(1, 1, 'admin'),
(2, 2, 'hod'),
(3, 3, 'secretary'),
(4, 4, 'advisor'),
(5, 5, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
-- CHANGES: Primary key renamed to user_id, department VARCHAR changed to department_id INT,
--          class VARCHAR changed to class_id INT, Changed to InnoDB
--

DROP TABLE IF EXISTS `user_details`;
CREATE TABLE IF NOT EXISTS `user_details` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `unique_id` varchar(20) DEFAULT NULL,
  `password` varchar(254) NOT NULL,
  `department_id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `level_id` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `unique_id` (`unique_id`),
  KEY `idx_role` (`role_id`),
  KEY `idx_department` (`department_id`),
  KEY `idx_class` (`class_id`),
  KEY `idx_level` (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
-- Note: department and class have been converted to IDs based on the original data
--

INSERT INTO `user_details` (`user_id`, `role_id`, `f_name`, `l_name`, `username`, `email`, `unique_id`, `password`, `department_id`, `class_id`, `level_id`, `is_active`) VALUES
(1, 1, 'Prosper', 'test', 'admin', 'admin@gmail.com', NULL, '$2y$10$/8rHe8tJ0jbySZvAlSFcX.lpn.5i353g2CaiBl9FDIs8bp9Opyqda', 2, NULL, NULL, 1),
(2, 2, 'Nii Adotei', 'Addo', 'Surnii', 'niiadot19@gmail.com', NULL, '$2y$10$/8rHe8tJ0jbySZvAlSFcX.lpn.5i353g2CaiBl9FDIs8bp9Opyqda', 2, NULL, NULL, 1),
(3, 3, 'Selorm', 'Fugar', 'Jselly01', 'ismailabdulaisaiku@gmail.com', NULL, '$2y$10$UUNNkASS34Rv788ESGhZsu5pJvH6CrTNTj5hGmBzmH67oPF0SOM2q', 2, NULL, NULL, 1),
(4, 2, 'S', 'A-N', 'HOD DOT', 'san@gmail.com', NULL, '$2y$10$/8rHe8tJ0jbySZvAlSFcX.lpn.5i353g2CaiBl9FDIs8bp9Opyqda', 1, NULL, NULL, 1),
(5, 2, 'Harry', 'Johnson', 'HOD MEE', 'harry@gmail.com', NULL, '$2y$10$/8rHe8tJ0jbySZvAlSFcX.lpn.5i353g2CaiBl9FDIs8bp9Opyqda', 3, NULL, NULL, 1),
(7, 2, 'Issac', 'Nyarko', 'HOD EE', 'q@gmail.com', NULL, '$2y$10$h9W549aZiR.Y6HxscCr7OOux8CpZLOTiSCd3oEsbgxu2QTraefefi', 4, NULL, NULL, 1),
(11, 4, 'Samuel', 'Enguah', 'L100 Advisor', 'sam@gmail.com', NULL, '$2y$10$CM6eFP4uHwbmrPVTMfSO/.k2Z0FBwfd.QVF1s4SsYz69g6dwroPca', 2, NULL, NULL, 1),
(12, 5, 'Jeff', 'Nyarko', NULL, 'jsf@gmail.com', 'RMUDMSHZOKWI', '$2y$10$/8rHe8tJ0jbySZvAlSFcX.lpn.5i353g2CaiBl9FDIs8bp9Opyqda', 2, 1, 1, 1),
(17, 5, 'Denzel', 'Curry', NULL, 'denzel@gmail.com', 'RMU13CVP90QZ', '$2y$10$AaOAyLJao4SrT2lNdqMRHu1vjI3COiUvr23XNUqseC.YzmsoIUaHS', 2, 2, 2, 1),
(18, 5, 'Hidaya', 'Sulemana', NULL, 'suleman@gmail.com', 'RMUYPD13BVJT', '$2y$10$zKUKBx6y0nEou.603bxVIO0qVCJilttgmgzQEZGetfphYN07Ypb5m', 2, 1, 1, 1),
(19, 5, 'Naila', 'Alhassan', NULL, 'naila@gmail.com', 'RMUZVI0GHLCY', '$2y$10$YLHTEFhhKFaUyaM8y3eWiuhSzxTrC/iYp2w7eUMrefpGLH/dC4gzq', 2, 1, 1, 1),
(20, 2, 'test', 'hod', 'hr', 'h@gmail.com', NULL, '$2y$10$2AI9SkGWdjUEFJENdQJlbufspQv4MiENm4YM/oCxhVKuZ9SdikYVS', 5, NULL, NULL, 1),
(21, 3, 'test', 'sec', 'meesec', 'y@gmail.com', NULL, '$2y$10$LsjKq4o66KmGrb6AYRL.FOAa9TIlSvoOMmUtmPWimmxAJ77odPBGe', 3, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `advisor_levels`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `advisor_levels`;
CREATE TABLE IF NOT EXISTS `advisor_levels` (
  `t_id` int NOT NULL AUTO_INCREMENT,
  `level_id` int NOT NULL,
  `department_id` int NOT NULL,
  `advisor_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`t_id`),
  KEY `idx_level` (`level_id`),
  KEY `idx_department` (`department_id`),
  KEY `idx_advisor` (`advisor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advisor_levels`
--

INSERT INTO `advisor_levels` (`t_id`, `level_id`, `department_id`, `advisor_id`) VALUES
(2, 1, 2, 11);

-- --------------------------------------------------------

--
-- Table structure for table `course_lecturers`
-- NEW TABLE: For HOD to assign lecturers to courses
--

DROP TABLE IF EXISTS `course_lecturers`;
CREATE TABLE IF NOT EXISTS `course_lecturers` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `lecturer_user_id` int NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `assigned_by` int NOT NULL COMMENT 'user_id of HOD who made assignment',
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`assignment_id`),
  KEY `idx_course` (`course_id`),
  KEY `idx_lecturer` (`lecturer_user_id`),
  KEY `idx_academic_year` (`academic_year_id`),
  KEY `idx_semester` (`semester_id`),
  KEY `idx_composite` (`course_id`,`academic_year_id`,`semester_id`),
  KEY `idx_assigned_by` (`assigned_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_tokens`
-- NEW TABLE: Pseudonymous token system for student evaluations
--

DROP TABLE IF EXISTS `evaluation_tokens`;
CREATE TABLE IF NOT EXISTS `evaluation_tokens` (
  `token_id` int NOT NULL AUTO_INCREMENT,
  `student_user_id` int NOT NULL,
  `course_id` int NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_student` (`student_user_id`),
  KEY `idx_course` (`course_id`),
  KEY `idx_academic_year` (`academic_year_id`),
  KEY `idx_semester` (`semester_id`),
  KEY `idx_is_used` (`is_used`),
  KEY `idx_composite` (`student_user_id`,`course_id`,`academic_year_id`,`semester_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
-- CHANGES: Removed student_id, Changed course_id to INT, Added token, academic_year_id, semester_id
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `evaluation_id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(64) NOT NULL,
  `course_id` int NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `evaluation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`evaluation_id`),
  KEY `idx_token` (`token`),
  KEY `idx_course` (`course_id`),
  KEY `idx_academic_year` (`academic_year_id`),
  KEY `idx_semester` (`semester_id`),
  KEY `idx_composite` (`course_id`,`academic_year_id`,`semester_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
-- Note: Historical data migrated with generated tokens
--

INSERT INTO `evaluations` (`evaluation_id`, `token`, `course_id`, `academic_year_id`, `semester_id`, `evaluation_date`) VALUES
(9, 'legacy_token_9_RMUDMSHZOKWI', 5, 1, 2, '2024-11-19 21:43:06');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_questions`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `evaluation_questions`;
CREATE TABLE IF NOT EXISTS `evaluation_questions` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `question_text` varchar(255) NOT NULL,
  `is_required` tinyint(1) DEFAULT '1',
  `category` varchar(50) DEFAULT 'General',
  `display_order` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`),
  KEY `idx_category` (`category`),
  KEY `idx_is_active` (`is_active`),
  KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_questions`
--

INSERT INTO `evaluation_questions` (`question_id`, `question_text`, `is_required`, `category`, `display_order`, `is_active`) VALUES
(1, 'I was briefed on the course overview and objective', 1, 'Questions', 1, 1),
(2, 'I am able to relate the theory to practical', 1, 'Questions', 2, 1),
(3, 'There is adequate practical content', 1, 'Questions', 3, 1),
(4, 'The lecture helped me to understand the learning materials', 1, 'Questions', 4, 1),
(5, 'I was encouraged to ask questions', 1, 'Questions', 5, 1),
(6, 'How would you rate the equipment (simulator, swimming pool, workshop) used for the practical session?', 1, 'Questions', 6, 1),
(7, 'How would you assess the handouts provided?', 1, 'Questions', 7, 1),
(8, 'Timetable was timely and adhered to', 1, 'Questions', 8, 1),
(9, 'Lecturer was available as scheduled', 1, 'Questions', 9, 1),
(10, 'How would you rate the performance of your class advisor?', 1, 'Questions', 10, 1),
(11, 'How would you rate the assessments conducted', 1, 'Assessment', 11, 1),
(12, 'Classroom environment was conducive to learning.', 1, 'Teaching and Learning Environment', 12, 1),
(13, 'How would you rate other facilities such as washrooms and surroundings?', 1, 'Washroom & Surroundings', 13, 1),
(14, 'Customer Service:  Staff were supportive', 1, 'Registry', 14, 1),
(15, 'Turnaround time: Waiting time was short ', 1, 'Registry', 15, 1),
(16, 'Feedback: received timely feedback on my request', 1, 'Registry', 16, 1),
(17, 'Customer Service:  Staff were supportive', 1, 'Accounts', 17, 1),
(18, 'Turnaround time: Waiting time was short ', 1, 'Accounts', 18, 1),
(19, 'Feedback: received timely feedback on my request', 1, 'Accounts', 19, 1),
(20, 'Customer Service:  Staff were supportive', 1, 'Library', 20, 1),
(21, 'Turnaround time: Waiting time was short ', 1, 'Library', 21, 1),
(22, 'Feedback: received timely feedback on my request', 1, 'Library', 22, 1),
(23, 'Customer Service:  Staff were supportive', 1, 'Administration', 23, 1),
(24, 'Turnaround time: Waiting time was short ', 1, 'Administration', 24, 1),
(25, 'Feedback: received timely feedback on my request', 1, 'Administration', 25, 1),
(26, 'Customer Service:  Staff were supportive', 1, 'Sickbay', 26, 1),
(27, 'Turnaround time: Waiting time was short ', 1, 'Sickbay', 27, 1),
(28, 'Feedback: received timely feedback on my request', 1, 'Sickbay', 28, 1),
(29, 'test question', 1, 'Washroom & Surroundings', 29, 1);

-- --------------------------------------------------------

--
-- Table structure for table `responses`
-- CHANGES: Added composite indexes, Changed to InnoDB
--

DROP TABLE IF EXISTS `responses`;
CREATE TABLE IF NOT EXISTS `responses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `evaluation_id` int NOT NULL,
  `question_id` int NOT NULL,
  `response_value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_evaluation` (`evaluation_id`),
  KEY `idx_question` (`question_id`),
  KEY `idx_composite` (`evaluation_id`,`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `responses`
--

INSERT INTO `responses` (`id`, `evaluation_id`, `question_id`, `response_value`) VALUES
(1, 9, 1, '3'),
(2, 9, 2, '3'),
(3, 9, 3, '3'),
(4, 9, 4, '3'),
(5, 9, 5, '3'),
(6, 9, 6, '3'),
(7, 9, 7, '3'),
(8, 9, 8, '3'),
(9, 9, 9, '3'),
(10, 9, 10, '3'),
(11, 9, 11, '5'),
(12, 9, 12, '5'),
(13, 9, 13, '5'),
(14, 9, 14, '1'),
(15, 9, 15, '1'),
(16, 9, 16, '1'),
(17, 9, 17, '1'),
(18, 9, 18, '1'),
(19, 9, 19, '1'),
(20, 9, 20, '3'),
(21, 9, 21, '2'),
(22, 9, 22, '2'),
(23, 9, 23, '3'),
(24, 9, 24, '2'),
(25, 9, 25, '2'),
(26, 9, 26, '2'),
(27, 9, 27, '4'),
(28, 9, 28, '4');

-- --------------------------------------------------------

--
-- Table structure for table `questions_archive`
-- CHANGES: Changed to InnoDB
--

DROP TABLE IF EXISTS `questions_archive`;
CREATE TABLE IF NOT EXISTS `questions_archive` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `question_text` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT 'General',
  `archived_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `archived_by` int DEFAULT NULL COMMENT 'user_id of admin who archived',
  PRIMARY KEY (`question_id`),
  KEY `idx_category` (`category`),
  KEY `idx_archived_by` (`archived_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
-- NEW TABLE: For tracking admin actions and maintaining security audit trail
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action_type` varchar(50) NOT NULL COMMENT 'INSERT, UPDATE, DELETE, LOGIN, LOGOUT, etc.',
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int DEFAULT NULL,
  `old_values` text COMMENT 'JSON format',
  `new_values` text COMMENT 'JSON format',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_action_type` (`action_type`),
  KEY `idx_table_name` (`table_name`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_composite` (`user_id`,`action_type`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- View for Department-Course Mapping (Helper for Reports)
--

CREATE OR REPLACE VIEW view_department_courses AS
SELECT 
    d.t_id AS department_id,
    d.dep_name,
    d.dep_code,
    c.id AS course_id,
    c.course_code,
    c.name AS course_name,
    c.level_id,
    c.semester_id,
    l.level_name
FROM courses c
JOIN department d ON c.department_id = d.t_id
JOIN level l ON c.level_id = l.t_id;

-- --------------------------------------------------------

--
-- View for Active Evaluation Period
--

CREATE OR REPLACE VIEW view_active_period AS
SELECT 
    ay.academic_year_id,
    ay.year_label AS academic_year,
    s.semester_id,
    s.semester_name,
    s.semester_value
FROM academic_year ay
JOIN semesters s ON ay.academic_year_id = s.academic_year_id
WHERE ay.is_active = 1 AND s.is_active = 1;

-- --------------------------------------------------------

--
-- View for Course Evaluation Statistics (with pseudonymity preserved)
--

CREATE OR REPLACE VIEW view_course_evaluation_stats AS
SELECT 
    e.course_id,
    c.course_code,
    c.name AS course_name,
    c.department_id,
    d.dep_name,
    e.academic_year_id,
    ay.year_label,
    e.semester_id,
    s.semester_name,
    COUNT(DISTINCT e.evaluation_id) AS total_evaluations,
    eq.question_id,
    eq.question_text,
    eq.category,
    AVG(CAST(r.response_value AS DECIMAL(10,2))) AS avg_response,
    STDDEV(CAST(r.response_value AS DECIMAL(10,2))) AS std_response,
    MIN(CAST(r.response_value AS DECIMAL(10,2))) AS min_response,
    MAX(CAST(r.response_value AS DECIMAL(10,2))) AS max_response,
    COUNT(r.id) AS response_count
FROM evaluations e
JOIN courses c ON e.course_id = c.id
JOIN department d ON c.department_id = d.t_id
JOIN academic_year ay ON e.academic_year_id = ay.academic_year_id
JOIN semesters s ON e.semester_id = s.semester_id
JOIN responses r ON e.evaluation_id = r.evaluation_id
JOIN evaluation_questions eq ON r.question_id = eq.question_id
GROUP BY 
    e.course_id, e.academic_year_id, e.semester_id, eq.question_id;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- ========================================
-- SUMMARY OF CHANGES
-- ========================================
-- 1. ✅ All tables converted to InnoDB
-- 2. ✅ Department relationships normalized (VARCHAR → INT)
-- 3. ✅ Pseudonymous evaluation system with tokens
-- 4. ✅ Removed UNIQUE constraint on academic_year.is_active
-- 5. ✅ Renamed active_semester to semesters with academic_year_id
-- 6. ✅ Renamed user_details primary key to user_id
-- 7. ✅ Added evaluation_tokens table
-- 8. ✅ Added course_lecturers table
-- 9. ✅ Added audit_logs table
-- 10. ✅ Proper indexing on all foreign key relationships
-- 11. ✅ Added useful views for reporting
-- 12. ✅ UTF8MB4 unicode collation for proper character support
-- 13. ✅ Timestamps on all tables for audit trail
-- 14. ✅ is_active flags for soft deletes
-- 15. ✅ Composite indexes for performance on common queries
