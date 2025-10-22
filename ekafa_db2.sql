-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for ekafa_db
CREATE DATABASE IF NOT EXISTS `ekafa_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ekafa_db`;

-- Dumping structure for table ekafa_db.class
CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `objectives` text COLLATE utf8mb4_unicode_ci,
  `prerequisites` text COLLATE utf8mb4_unicode_ci,
  `grade_level` enum('Pre-School','Year 1','Year 2','Year 3','Year 4','Year 5','Year 6') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_age` int DEFAULT NULL,
  `max_age` int DEFAULT NULL,
  `year` int NOT NULL,
  `session_id` int unsigned DEFAULT NULL,
  `session_type` enum('Morning','Evening') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `days_of_week` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `classroom_location` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `building` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `floor` int DEFAULT NULL,
  `user_id` int unsigned NOT NULL,
  `assistant_teacher_id` int unsigned DEFAULT NULL,
  `quota` int NOT NULL,
  `minimum_enrollment` int DEFAULT '5',
  `monthly_fee` decimal(10,2) DEFAULT NULL,
  `registration_fee` decimal(10,2) DEFAULT NULL,
  `current_enrollment` int NOT NULL DEFAULT '0',
  `waiting_list_count` int DEFAULT '0',
  `status` enum('Draft','Open','Closed','Full','In Progress','Completed','Cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'Draft',
  `is_visible` tinyint(1) DEFAULT '1',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int unsigned DEFAULT NULL,
  `enrollment_start_date` date DEFAULT NULL,
  `enrollment_end_date` date DEFAULT NULL,
  `class_start_date` date DEFAULT NULL,
  `class_end_date` date DEFAULT NULL,
  PRIMARY KEY (`class_id`),
  KEY `idx_class_user_id` (`user_id`),
  KEY `fk_class_assistant_teacher` (`assistant_teacher_id`),
  KEY `fk_class_created_by` (`created_by`),
  KEY `idx_class_teacher` (`user_id`),
  KEY `idx_class_session` (`session_id`),
  KEY `idx_class_year` (`year`),
  KEY `idx_class_status` (`status`),
  KEY `idx_class_visible` (`is_visible`),
  KEY `idx_class_grade_level` (`grade_level`),
  KEY `idx_class_session_type` (`session_type`),
  KEY `idx_class_enrollment` (`enrollment_start_date`,`enrollment_end_date`),
  CONSTRAINT `fk_class_assistant_teacher` FOREIGN KEY (`assistant_teacher_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_class_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_class_teacher` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ekafa_db.class: ~0 rows (approximately)

-- Dumping structure for table ekafa_db.class_backup_20251023
CREATE TABLE IF NOT EXISTS `class_backup_20251023` (
  `class_id` int unsigned NOT NULL DEFAULT '0',
  `class_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` int NOT NULL,
  `session_type` enum('Morning','Evening') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  `quota` int NOT NULL,
  `current_enrollment` int NOT NULL DEFAULT '0',
  `status` enum('Open','Closed','Full') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.class_backup_20251023: ~0 rows (approximately)

-- Dumping structure for table ekafa_db.documents
CREATE TABLE IF NOT EXISTS `documents` (
  `document_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `uploaded_by` int unsigned DEFAULT NULL,
  `verified_by` int unsigned DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `document_type` varchar(100) NOT NULL,
  `document_name` varchar(255) NOT NULL DEFAULT 'Untitled',
  `version` int DEFAULT '1',
  `is_latest_version` tinyint(1) DEFAULT '1',
  `original_filename` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) NOT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_hash` varchar(64) DEFAULT NULL,
  `status` enum('Pending Review','Approved','Rejected','Expired','Replaced','Deleted') DEFAULT 'Pending Review',
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiry_date` date DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `admin_notes` text,
  `rejection_reason` text,
  `owner_type` enum('User','Student','Teacher','Partner') DEFAULT NULL,
  `owner_id` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`document_id`),
  KEY `fk_documents_user` (`user_id`),
  KEY `fk_documents_category` (`category_id`),
  KEY `fk_documents_uploaded_by` (`uploaded_by`),
  KEY `fk_documents_verified_by` (`verified_by`),
  KEY `idx_documents_owner` (`owner_type`,`owner_id`),
  KEY `idx_documents_status` (`status`),
  KEY `idx_documents_file_hash` (`file_hash`),
  KEY `idx_documents_latest_version` (`is_latest_version`),
  CONSTRAINT `fk_documents_category` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_documents_uploaded_by` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_documents_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_documents_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.documents: ~0 rows (approximately)

-- Dumping structure for table ekafa_db.document_categories
CREATE TABLE IF NOT EXISTS `document_categories` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` text,
  `required_for_role` enum('Teacher','Parent','Both') NOT NULL DEFAULT 'Both',
  `is_required` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.document_categories: ~5 rows (approximately)
INSERT INTO `document_categories` (`category_id`, `category_name`, `description`, `required_for_role`, `is_required`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Birth Certificate', 'Student birth certificate', 'Parent', 1, 'Active', '2025-10-22 01:21:31', '2025-10-22 01:21:31'),
	(2, 'Parent IC Copy', 'Copy of parent identification card', 'Parent', 1, 'Active', '2025-10-22 01:21:31', '2025-10-22 01:21:31'),
	(3, 'Teaching Certificate', 'Professional teaching certificate', 'Teacher', 1, 'Active', '2025-10-22 01:21:31', '2025-10-22 01:21:31'),
	(4, 'Academic Transcript', 'Latest academic transcript', 'Teacher', 0, 'Active', '2025-10-22 01:21:31', '2025-10-22 01:21:31'),
	(5, 'Medical Report', 'Medical fitness report', 'Both', 0, 'Active', '2025-10-22 01:21:31', '2025-10-22 01:21:31');

-- Dumping structure for table ekafa_db.migration
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.migration: ~1 rows (approximately)
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1761095548),
	('m251022_011107_add_document_categories_table', 1761096091),
	('m251023_010000_enhance_users_table', 1761122121),
	('m251023_020000_rename_user_details_to_profiles', 1761122724),
	('m251023_030000_rename_documents_table', 1761122905),
	('m251023_040000_rename_user_jobs_table', 1761123305),
	('m251023_050000_enhance_partner_details', 1761124466),
	('m251023_060000_enhance_partner_job', 1761124500),
	('m251023_070000_enhance_classes_table', 1761124535);

-- Dumping structure for table ekafa_db.partner_details
CREATE TABLE IF NOT EXISTS `partner_details` (
  `partner_id` int unsigned NOT NULL,
  `partner_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_ic_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ic_copy_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') COLLATE utf8mb4_general_ci DEFAULT 'Unknown',
  `has_health_conditions` tinyint(1) DEFAULT '0',
  `health_conditions_details` text COLLATE utf8mb4_general_ci,
  `race` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `religion` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `education_level` enum('No Formal Education','Primary','Secondary','Diploma','Bachelor','Master','PhD','Other') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alternative_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emergency_contact_phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emergency_contact_relationship` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_citizenship` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_marital_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `relationship_to_user` enum('Spouse','Ex-Spouse','Partner','Other') COLLATE utf8mb4_general_ci DEFAULT 'Spouse',
  `marriage_date` date DEFAULT NULL,
  `marriage_certificate_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `divorce_date` date DEFAULT NULL,
  `divorce_certificate_no` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_address` text COLLATE utf8mb4_general_ci,
  `partner_city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_postcode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_general_ci DEFAULT 'Malaysia',
  `profile_picture_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Active','Inactive','Divorced','Deceased') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_by` int unsigned DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_id`),
  KEY `idx_partner_ic` (`partner_ic_number`),
  KEY `idx_partner_status` (`status`),
  KEY `idx_partner_verified` (`is_verified`),
  KEY `idx_partner_relationship` (`relationship_to_user`),
  KEY `fk_partner_details_verified_by` (`verified_by`),
  CONSTRAINT `fk_partner_details_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_partner_user` FOREIGN KEY (`partner_id`) REFERENCES `user_profiles` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ekafa_db.partner_details: ~1 rows (approximately)
INSERT INTO `partner_details` (`partner_id`, `partner_name`, `partner_ic_number`, `ic_copy_url`, `date_of_birth`, `gender`, `blood_type`, `has_health_conditions`, `health_conditions_details`, `race`, `religion`, `education_level`, `partner_phone_number`, `email`, `alternative_phone`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relationship`, `partner_citizenship`, `partner_marital_status`, `relationship_to_user`, `marriage_date`, `marriage_certificate_no`, `divorce_date`, `divorce_certificate_no`, `partner_address`, `partner_city`, `partner_postcode`, `partner_state`, `country`, `profile_picture_url`, `status`, `is_verified`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
	(3, 'Nurin Nadhira', '030212121089', NULL, '2003-02-12', 'Female', 'Unknown', 0, NULL, NULL, NULL, NULL, '0198000899', NULL, NULL, NULL, NULL, NULL, 'Malaysia', 'yes', 'Spouse', NULL, NULL, NULL, NULL, 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'Malaysia', 'uploads/partner_3_1760382657.jpg', 'Active', 0, NULL, NULL, '2025-10-22 17:14:26', '2025-10-22 17:14:26');

-- Dumping structure for table ekafa_db.partner_details_backup_20251023
CREATE TABLE IF NOT EXISTS `partner_details_backup_20251023` (
  `partner_id` int unsigned NOT NULL,
  `partner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `partner_ic_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `partner_phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_citizenship` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_marital_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `partner_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_postcode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_picture_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.partner_details_backup_20251023: ~0 rows (approximately)
INSERT INTO `partner_details_backup_20251023` (`partner_id`, `partner_name`, `partner_ic_number`, `partner_phone_number`, `partner_citizenship`, `partner_marital_status`, `partner_address`, `partner_city`, `partner_postcode`, `partner_state`, `profile_picture_url`) VALUES
	(3, 'Nurin Nadhira', '030212121089', '0198000899', 'Malaysia', 'yes', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/partner_3_1760382657.jpg');

-- Dumping structure for table ekafa_db.partner_job
CREATE TABLE IF NOT EXISTS `partner_job` (
  `partner_id` int unsigned NOT NULL,
  `partner_job` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employment_type` enum('Full-Time','Part-Time','Contract','Freelance','Self-Employed','Unemployed') COLLATE utf8mb4_general_ci DEFAULT 'Full-Time',
  `working_hours_per_week` int DEFAULT NULL,
  `employment_status` enum('Active','Resigned','Terminated','Retired') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `partner_employer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `partner_employer_address` text COLLATE utf8mb4_general_ci,
  `employment_letter_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latest_payslip_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_employer_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_gross_salary` decimal(10,2) DEFAULT NULL,
  `partner_net_salary` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_general_ci DEFAULT 'MYR',
  `other_income` decimal(10,2) DEFAULT '0.00',
  `other_income_source` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tax_identification_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `epf_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `socso_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_by` int unsigned DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_id`),
  KEY `idx_partner_job_status` (`employment_status`),
  KEY `idx_partner_job_type` (`employment_type`),
  KEY `idx_partner_job_verified` (`is_verified`),
  KEY `idx_partner_job_dates` (`start_date`,`end_date`),
  KEY `fk_partner_job_verified_by` (`verified_by`),
  CONSTRAINT `fk_partner_job` FOREIGN KEY (`partner_id`) REFERENCES `partner_details` (`partner_id`),
  CONSTRAINT `fk_partner_job_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ekafa_db.partner_job: ~0 rows (approximately)
INSERT INTO `partner_job` (`partner_id`, `partner_job`, `job_title`, `department`, `employment_type`, `working_hours_per_week`, `employment_status`, `partner_employer`, `start_date`, `end_date`, `partner_employer_address`, `employment_letter_url`, `latest_payslip_url`, `partner_employer_phone_number`, `partner_gross_salary`, `partner_net_salary`, `currency`, `other_income`, `other_income_source`, `tax_identification_number`, `epf_number`, `socso_number`, `is_verified`, `verified_by`, `verified_at`, `notes`, `created_at`, `updated_at`) VALUES
	(3, 'Lecturer', 'Lecturer', NULL, 'Full-Time', 40, 'Active', 'Universiti Malaysia Sabah', NULL, NULL, 'Universiti Malaysia Sabah, Jalan UMS, 88400 Kota Kinabalu, Sabah, Malaysia.', NULL, NULL, '0198008999', 5000.00, 4000.00, 'MYR', 0.00, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-10-22 17:15:00', '2025-10-22 17:15:00');

-- Dumping structure for table ekafa_db.partner_job_backup_20251023
CREATE TABLE IF NOT EXISTS `partner_job_backup_20251023` (
  `partner_id` int unsigned NOT NULL,
  `partner_job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_employer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_employer_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `partner_employer_phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_gross_salary` decimal(10,2) DEFAULT NULL,
  `partner_net_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.partner_job_backup_20251023: ~0 rows (approximately)
INSERT INTO `partner_job_backup_20251023` (`partner_id`, `partner_job`, `partner_employer`, `partner_employer_address`, `partner_employer_phone_number`, `partner_gross_salary`, `partner_net_salary`) VALUES
	(3, 'Lecturer', 'Universiti Malaysia Sabah', 'Universiti Malaysia Sabah, Jalan UMS, 88400 Kota Kinabalu, Sabah, Malaysia.', '0198008999', 5000.00, 4000.00);

-- Dumping structure for table ekafa_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('Admin','Teacher','Parent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT '10',
  `date_registered` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`),
  UNIQUE KEY `verification_token` (`verification_token`),
  KEY `idx_users_status` (`status`),
  KEY `idx_users_auth_key` (`auth_key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ekafa_db.users: ~3 rows (approximately)
INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `auth_key`, `password_reset_token`, `verification_token`, `role`, `status`, `date_registered`, `last_login`, `created_at`, `updated_at`) VALUES
	(1, 'aqil bin sulaiman', 'aqil007@gmail.com', '$2y$13$/RHgEL8E0ThSMMm/ThtlWebpLzgk0oFJgULgzXnKYXwY6qQ7XUt9S', 'ba877fb5c7533ae6fc8a3e60cde37e33', NULL, NULL, 'Parent', 10, '2025-07-31 03:15:43', '2025-10-20 06:06:29', 1753902943, 1760911589),
	(3, 'Fadzil Bin Ismail', 'fadzzf8@gmail.com', '$2y$13$Tf22tlTFZ0YM8EgbNqDbUeoWuGBFheBswn2Yz/K/JuH4SOFTA6iPa', '4efe3244f84ee21efd4cdf47bf6a2c3a', NULL, NULL, 'Admin', 10, '2025-07-31 09:37:58', '2025-10-22 01:57:55', 1753925878, 1761069475),
	(4, 'Hakimi Bin Suffian', 'Hakimi007@gmail.com', '$2y$13$ni3ZT5.GtfoidTdZVBfOjuK7AIRDqyQznBTmXOm2Q/PE.mNiAocee', 'd303e2480e8c4922c672e369ce5b9a04', NULL, NULL, 'Teacher', 10, '2025-07-31 03:40:19', '2025-10-22 02:34:50', 1753904419, 1761071690);

-- Dumping structure for table ekafa_db.users_backup_20251023
CREATE TABLE IF NOT EXISTS `users_backup_20251023` (
  `user_id` int unsigned NOT NULL DEFAULT '0',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Teacher','Parent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_registered` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.users_backup_20251023: ~3 rows (approximately)
INSERT INTO `users_backup_20251023` (`user_id`, `username`, `email`, `password_hash`, `role`, `date_registered`, `last_login`) VALUES
	(1, 'aqil bin sulaiman', 'aqil007@gmail.com', '$2y$13$/RHgEL8E0ThSMMm/ThtlWebpLzgk0oFJgULgzXnKYXwY6qQ7XUt9S', 'Parent', '2025-07-31 03:15:43', '2025-10-20 06:06:29'),
	(3, 'Fadzil Bin Ismail', 'fadzzf8@gmail.com', '$2y$13$Tf22tlTFZ0YM8EgbNqDbUeoWuGBFheBswn2Yz/K/JuH4SOFTA6iPa', 'Admin', '2025-07-31 09:37:58', '2025-10-22 01:57:55'),
	(4, 'Hakimi Bin Suffian', 'Hakimi007@gmail.com', '$2y$13$ni3ZT5.GtfoidTdZVBfOjuK7AIRDqyQznBTmXOm2Q/PE.mNiAocee', 'Teacher', '2025-07-31 03:40:19', '2025-10-22 02:34:50');

-- Dumping structure for table ekafa_db.user_details_backup_20251023
CREATE TABLE IF NOT EXISTS `user_details_backup_20251023` (
  `user_details_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ic_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` enum('Male','Female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizenship` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.user_details_backup_20251023: ~3 rows (approximately)
INSERT INTO `user_details_backup_20251023` (`user_details_id`, `user_id`, `full_name`, `ic_number`, `age`, `gender`, `race`, `phone_number`, `citizenship`, `marital_status`, `address`, `city`, `postcode`, `state`, `profile_picture_url`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Aqil Bin Sulaiman', '030211121097', 22, 'Male', 'Bajau', '0128151363', 'Malaysia', 'single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_1_1753925146.jpg', '2025-07-31 03:25:46', '2025-07-31 03:25:46'),
	(2, 3, 'Fadzil Bin Ismail', '030212121097', 22, 'Male', 'Bajau', '0128151363', 'Malaysia', 'Single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_3_1760249639.png', '2025-07-31 03:43:08', '2025-10-21 02:27:43'),
	(3, 4, 'Mohd Hakimi Bin Suffian', '0300930131009', 22, 'Male', 'Melanau', '0128151363', 'Malaysia', 'single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_4_1753927271.jpg', '2025-07-31 04:01:11', '2025-07-31 04:01:11');

-- Dumping structure for table ekafa_db.user_documents_backup_20251023
CREATE TABLE IF NOT EXISTS `user_documents_backup_20251023` (
  `document_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `status` enum('Completed','Incomplete','Pending Review','Rejected') DEFAULT 'Pending Review',
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `category_id` int DEFAULT NULL,
  `admin_notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.user_documents_backup_20251023: ~0 rows (approximately)

-- Dumping structure for table ekafa_db.user_jobs_backup_20251023
CREATE TABLE IF NOT EXISTS `user_jobs_backup_20251023` (
  `userJob_id` int unsigned NOT NULL DEFAULT '0',
  `user_id` int unsigned NOT NULL,
  `job` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employer_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `employer_phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table ekafa_db.user_jobs_backup_20251023: ~2 rows (approximately)
INSERT INTO `user_jobs_backup_20251023` (`userJob_id`, `user_id`, `job`, `employer`, `employer_address`, `employer_phone_number`, `gross_salary`, `net_salary`) VALUES
	(1, 3, 'Software Engineer', 'Pusat Islam UMS', 'Pusat Islam Universiti Malaysia Sabah, 88400 Kota Kinabalu, Sabah, Malaysia', '0128151363', 1000.00, 500.00),
	(2, 1, 'Lawyer', 'Majlis Hakim', 'Mahkamah Kota Kinabalu, 88400 Kota Kinabalu, Sabah', '-', 5000.00, 3000.00);

-- Dumping structure for table ekafa_db.user_job_details
CREATE TABLE IF NOT EXISTS `user_job_details` (
  `job_detail_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `job` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `job_title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `department` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employment_type` enum('Full-Time','Part-Time','Contract','Freelance','Self-Employed','Unemployed') COLLATE utf8mb4_general_ci DEFAULT 'Full-Time',
  `working_hours_per_week` int DEFAULT NULL,
  `employment_status` enum('Active','Resigned','Terminated','Retired') COLLATE utf8mb4_general_ci DEFAULT 'Active',
  `employer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `employer_address` text COLLATE utf8mb4_general_ci,
  `employment_letter_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latest_payslip_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employer_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8mb4_general_ci DEFAULT 'MYR',
  `tax_identification_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `epf_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `socso_number` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT '0',
  `verified_by` int unsigned DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `other_income` decimal(10,2) DEFAULT '0.00',
  `other_income_source` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`job_detail_id`),
  KEY `fk_user_jobs_user` (`user_id`),
  KEY `fk_user_job_details_verified_by` (`verified_by`),
  KEY `idx_job_details_employment_status` (`employment_status`),
  KEY `idx_job_details_employment_type` (`employment_type`),
  KEY `idx_job_details_verified` (`is_verified`),
  KEY `idx_job_details_dates` (`start_date`,`end_date`),
  CONSTRAINT `fk_user_job_details_user` FOREIGN KEY (`user_id`) REFERENCES `user_profiles` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_job_details_verified_by` FOREIGN KEY (`verified_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table ekafa_db.user_job_details: ~2 rows (approximately)
INSERT INTO `user_job_details` (`job_detail_id`, `user_id`, `job`, `job_title`, `department`, `employment_type`, `working_hours_per_week`, `employment_status`, `employer`, `start_date`, `end_date`, `employer_address`, `employment_letter_url`, `latest_payslip_url`, `employer_phone_number`, `gross_salary`, `net_salary`, `currency`, `tax_identification_number`, `epf_number`, `socso_number`, `is_verified`, `verified_by`, `verified_at`, `created_at`, `updated_at`, `other_income`, `other_income_source`) VALUES
	(1, 3, 'Software Engineer', 'Software Engineer', NULL, 'Full-Time', 40, 'Active', 'Pusat Islam UMS', NULL, NULL, 'Pusat Islam Universiti Malaysia Sabah, 88400 Kota Kinabalu, Sabah, Malaysia', NULL, NULL, '0128151363', 1000.00, 500.00, 'MYR', NULL, NULL, NULL, 0, NULL, NULL, '2025-10-22 16:55:04', '2025-10-22 16:55:04', 0.00, NULL),
	(2, 1, 'Lawyer', 'Lawyer', NULL, 'Full-Time', 40, 'Active', 'Majlis Hakim', NULL, NULL, 'Mahkamah Kota Kinabalu, 88400 Kota Kinabalu, Sabah', NULL, NULL, '-', 5000.00, 3000.00, 'MYR', NULL, NULL, NULL, 0, NULL, NULL, '2025-10-22 16:55:04', '2025-10-22 16:55:04', 0.00, NULL);

-- Dumping structure for table ekafa_db.user_profiles
CREATE TABLE IF NOT EXISTS `user_profiles` (
  `user_details_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ic_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact_relationship` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizenship` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Malaysia',
  `profile_picture_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','Unknown') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_details_id`),
  UNIQUE KEY `ic_number` (`ic_number`),
  UNIQUE KEY `unique_user_id` (`user_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_user_profiles_blood_type` (`blood_type`),
  KEY `idx_user_profiles_date_of_birth` (`date_of_birth`),
  CONSTRAINT `fk_user_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table ekafa_db.user_profiles: ~3 rows (approximately)
INSERT INTO `user_profiles` (`user_details_id`, `user_id`, `full_name`, `ic_number`, `age`, `date_of_birth`, `gender`, `race`, `phone_number`, `emergency_phone`, `emergency_contact_name`, `emergency_contact_relationship`, `citizenship`, `marital_status`, `occupation`, `address`, `city`, `postcode`, `state`, `country`, `profile_picture_url`, `created_at`, `updated_at`, `blood_type`) VALUES
	(1, 1, 'Aqil Bin Sulaiman', '030211121097', 22, '2003-02-11', 'Male', 'Bajau', '0128151363', NULL, NULL, NULL, 'Malaysia', 'single', NULL, 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'Malaysia', 'uploads/user_1_1753925146.jpg', '2025-07-31 03:25:46', '2025-07-31 03:25:46', NULL),
	(2, 3, 'Fadzil Bin Ismail', '030212121097', 22, '2003-02-12', 'Male', 'Bajau', '0128151363', NULL, NULL, NULL, 'Malaysia', 'Single', NULL, 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'Malaysia', 'uploads/user_3_1760249639.png', '2025-07-31 03:43:08', '2025-10-21 02:27:43', NULL),
	(3, 4, 'Mohd Hakimi Bin Suffian', '0300930131009', 22, NULL, 'Male', 'Melanau', '0128151363', NULL, NULL, NULL, 'Malaysia', 'single', NULL, 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'Malaysia', 'uploads/user_4_1753927271.jpg', '2025-07-31 04:01:11', '2025-07-31 04:01:11', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
