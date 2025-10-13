-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 01, 2025 at 02:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARekafa_dbACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ekafa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(10) UNSIGNED NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `year` int(11) NOT NULL,
  `session_type` enum('Morning','Evening') NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `quota` int(11) NOT NULL,
  `current_enrollment` int(11) NOT NULL DEFAULT 0,
  `status` enum('Open','Closed','Full') NOT NULL DEFAULT 'Open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partner_details`
--

CREATE TABLE `partner_details` (
  `partner_id` int(10) UNSIGNED NOT NULL,
  `partner_name` varchar(255) NOT NULL,
  `partner_ic_number` varchar(20) NOT NULL,
  `partner_phone_number` varchar(20) DEFAULT NULL,
  `partner_citizenship` varchar(100) DEFAULT NULL,
  `partner_marital_status` varchar(50) DEFAULT NULL,
  `partner_address` text DEFAULT NULL,
  `partner_city` varchar(100) DEFAULT NULL,
  `partner_postcode` varchar(10) DEFAULT NULL,
  `partner_state` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partner_job`
--

CREATE TABLE `partner_job` (
  `partner_id` int(10) UNSIGNED NOT NULL,
  `partner_job` varchar(100) DEFAULT NULL,
  `partner_employer` varchar(255) DEFAULT NULL,
  `partner_employer_address` text DEFAULT NULL,
  `partner_employer_phone_number` varchar(20) DEFAULT NULL,
  `partner_gross_salary` decimal(10,2) DEFAULT NULL,
  `partner_net_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('Admin','Teacher','Parent') NOT NULL,
  `date_registered` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `date_registered`, `last_login`) VALUES
(1, 'aqil bin sulaiman', 'aqil007@gmail.com', '$2y$13$/RHgEL8E0ThSMMm/ThtlWebpLzgk0oFJgULgzXnKYXwY6qQ7XUt9S', 'Parent', '2025-07-31 03:15:43', '2025-08-28 06:32:22'),
(3, 'Fadzil Bin Ismail', 'fadzzf8@gmail.com', '$2y$13$Tf22tlTFZ0YM8EgbNqDbUeoWuGBFheBswn2Yz/K/JuH4SOFTA6iPa', 'Admin', '2025-07-31 09:37:58', '2025-08-28 06:30:03'),
(4, 'Hakimi Bin Suffian', 'Hakimi007@gmail.com', '$2y$13$ni3ZT5.GtfoidTdZVBfOjuK7AIRDqyQznBTmXOm2Q/PE.mNiAocee', 'Teacher', '2025-07-31 03:40:19', '2025-07-31 03:58:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `user_details_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `ic_number` varchar(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `citizenship` varchar(100) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`user_details_id`, `user_id`, `full_name`, `ic_number`, `age`, `gender`, `race`, `phone_number`, `citizenship`, `marital_status`, `address`, `city`, `postcode`, `state`, `profile_picture_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'Aqil Bin Sulaiman', '030211121097', 22, 'Male', 'Bajau', '0128151363', 'Malaysia', 'single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_1_1753925146.jpg', '2025-07-31 03:25:46', '2025-07-31 03:25:46'),
(2, 3, 'Fadzil Bin Ismail', '030212121097', 22, 'Male', 'Bajau', '0128151363', 'Malaysia', 'single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_3_1753926188.jpg', '2025-07-31 03:43:08', '2025-07-31 03:43:08'),
(3, 4, 'Mohd Hakimi Bin Suffian', '0300930131009', 22, 'Male', 'Melanau', '0128151363', 'Malaysia', 'single', 'No 157, Taman Layar Impian', 'Tuaran', '89208', 'Sabah', 'uploads/user_4_1753927271.jpg', '2025-07-31 04:01:11', '2025-07-31 04:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_jobs`
--

CREATE TABLE `user_jobs` (
  `userJob_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `job` varchar(100) DEFAULT NULL,
  `employer` varchar(255) DEFAULT NULL,
  `employer_address` text DEFAULT NULL,
  `employer_phone_number` varchar(20) DEFAULT NULL,
  `gross_salary` decimal(10,2) DEFAULT NULL,
  `net_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `idx_class_user_id` (`user_id`);

--
-- Indexes for table `partner_details`
--
ALTER TABLE `partner_details`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `partner_job`
--
ALTER TABLE `partner_job`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_details_id`),
  ADD UNIQUE KEY `ic_number` (`ic_number`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `user_jobs`
--
ALTER TABLE `user_jobs`
  ADD PRIMARY KEY (`userJob_id`),
  ADD KEY `fk_user_jobs_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `user_details_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_jobs`
--
ALTER TABLE `user_jobs`
  MODIFY `userJob_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `fk_class_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `partner_details`
--
ALTER TABLE `partner_details`
  ADD CONSTRAINT `fk_partner_user` FOREIGN KEY (`partner_id`) REFERENCES `user_details` (`user_details_id`);

--
-- Constraints for table `partner_job`
--
ALTER TABLE `partner_job`
  ADD CONSTRAINT `fk_partner_job` FOREIGN KEY (`partner_id`) REFERENCES `partner_details` (`partner_id`);

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `fk_user_details_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_jobs`
--
ALTER TABLE `user_jobs`
  ADD CONSTRAINT `fk_user_jobs_user` FOREIGN KEY (`user_id`) REFERENCES `user_details` (`user_details_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
