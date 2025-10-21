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
  `year` int NOT NULL,
  `session_type` enum('Morning','Evening') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int unsigned NOT NULL,
  `quota` int NOT NULL,
  `current_enrollment` int NOT NULL DEFAULT '0',
  `status` enum('Open','Closed','Full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Open',
  PRIMARY KEY (`class_id`),
  KEY `idx_class_user_id` (`user_id`),
  CONSTRAINT `fk_class_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.partner_details
CREATE TABLE IF NOT EXISTS `partner_details` (
  `partner_id` int unsigned NOT NULL,
  `partner_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_ic_number` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `partner_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_citizenship` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_marital_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_address` text COLLATE utf8mb4_general_ci,
  `partner_city` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_postcode` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_state` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `profile_picture_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.partner_job
CREATE TABLE IF NOT EXISTS `partner_job` (
  `partner_id` int unsigned NOT NULL,
  `partner_job` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_employer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_employer_address` text COLLATE utf8mb4_general_ci,
  `partner_employer_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `partner_gross_salary` decimal(10,2) DEFAULT NULL,
  `partner_net_salary` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`partner_id`),
  CONSTRAINT `fk_partner_job` FOREIGN KEY (`partner_id`) REFERENCES `partner_details` (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('Admin','Teacher','Parent') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_registered` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.user_details
CREATE TABLE IF NOT EXISTS `user_details` (
  `user_details_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ic_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `gender` enum('Male','Female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `race` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `citizenship` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_details_id`),
  UNIQUE KEY `ic_number` (`ic_number`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `fk_user_details_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.user_documents
CREATE TABLE IF NOT EXISTS `user_documents` (
  `document_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `status` enum('Completed','Incomplete','Pending Review') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `upload_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`document_id`),
  KEY `fk_user_documents_user` (`user_id`),
  CONSTRAINT `fk_user_documents_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Data exporting was unselected.

-- Dumping structure for table ekafa_db.user_jobs
CREATE TABLE IF NOT EXISTS `user_jobs` (
  `userJob_id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `job` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employer` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `employer_address` text COLLATE utf8mb4_general_ci,
  `employer_phone_number` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`userJob_id`),
  KEY `fk_user_jobs_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
