-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2025 at 02:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beacon_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) UNSIGNED NOT NULL,
  `region` varchar(100) NOT NULL,
  `city_municipality` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `region`, `city_municipality`, `barangay`, `created_at`, `updated_at`) VALUES
(1, 'Roman', 'geds', 'dzfgvdsfgv', '2025-11-25 02:39:48', '2025-11-25 02:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `organization_name` varchar(200) NOT NULL,
  `organization_acronym` varchar(20) NOT NULL,
  `organization_type` enum('academic','non_academic','service','religious','cultural','sports','other') NOT NULL,
  `organization_category` enum('departmental','inter_departmental','university_wide') NOT NULL,
  `founding_date` date NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `objectives` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `current_members` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `user_id`, `organization_name`, `organization_acronym`, `organization_type`, `organization_category`, `founding_date`, `mission`, `vision`, `objectives`, `contact_email`, `password_hash`, `contact_phone`, `current_members`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 3, 'Student Council', 'CSS', 'academic', 'departmental', '2025-11-25', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', 'marhernandez@my.cspc.edu.ph', '', '09512741049', 6, 1, '2025-11-25 21:09:06', '2025-11-25 21:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `organization_advisors`
--

CREATE TABLE `organization_advisors` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `department` enum('ccs','cea','cthbm','chs','ctde','cas','gs') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_advisors`
--

INSERT INTO `organization_advisors` (`id`, `application_id`, `name`, `email`, `phone`, `department`, `created_at`, `updated_at`) VALUES
(5, 5, 'Mariel Hernandez', 'irespeleta@ny.cspc.edu.ph', '09512456567', 'ccs', '2025-11-25 21:08:52', '2025-11-25 21:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `organization_applications`
--

CREATE TABLE `organization_applications` (
  `id` int(11) UNSIGNED NOT NULL,
  `organization_name` varchar(200) NOT NULL,
  `organization_acronym` varchar(20) NOT NULL,
  `organization_type` enum('academic','non_academic','service','religious','cultural','sports','other') NOT NULL,
  `organization_category` enum('departmental','inter_departmental','university_wide') NOT NULL,
  `founding_date` date NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `objectives` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `contact_phone` varchar(20) NOT NULL,
  `current_members` int(11) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `reviewed_by` int(11) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `submitted_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_applications`
--

INSERT INTO `organization_applications` (`id`, `organization_name`, `organization_acronym`, `organization_type`, `organization_category`, `founding_date`, `mission`, `vision`, `objectives`, `contact_email`, `password_hash`, `contact_phone`, `current_members`, `status`, `admin_notes`, `reviewed_by`, `reviewed_at`, `submitted_at`, `created_at`, `updated_at`) VALUES
(5, 'Student Council', 'CSS', 'academic', 'departmental', '2025-11-25', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', '\"To empower students through accessible, meaningful, and community-driven programs that support growth, engagement, and lifelong learning.\"\r\n\r\n2.\r\n\r\n\"To foster a collaborative and inclusive environment where students are inspired to learn, lead, and create positive impact.\"\r\n\r\n3.\r\n\r\n\"To provide high-quality services and opportunities that enhance student success, promote involvement, and strengthen campus community.\"\r\n\r\n4.\r\n\r\n\"To support student development by delivering programs and initiatives that encourage excellence, leadership, and active participation.\"\r\n\r\n5.\r\n\r\n\"To enrich the student experience through innovative services, transparent communication, and a commitment to holistic growth.\"', 'marhernandez@my.cspc.edu.ph', '$2y$10$z.LikCEy.oWOayVgXMfgseWHfd.gdvh.uBrBdYJmin6MqM.XqG7uC', '09512741049', 6, 'approved', NULL, 1, '2025-11-25 13:09:06', '2025-11-25 13:08:52', '2025-11-25 21:08:52', '2025-11-25 21:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `organization_files`
--

CREATE TABLE `organization_files` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `file_type` enum('constitution','certification') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_size` int(11) UNSIGNED DEFAULT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_files`
--

INSERT INTO `organization_files` (`id`, `application_id`, `file_type`, `file_name`, `file_path`, `file_size`, `mime_type`, `uploaded_at`) VALUES
(9, 5, 'constitution', '404 Not Founders_MP.pdf', 'uploads/organizations/1764076132_677e98ca4b369da08727.pdf', 1012490, 'application/pdf', '2025-11-25 21:08:52'),
(10, 5, 'certification', 'boxplot.JPG', 'uploads/organizations/1764076132_a0024f2e0721512018ed.jpg', 23170, 'image/jpeg', '2025-11-25 21:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `organization_officers`
--

CREATE TABLE `organization_officers` (
  `id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `position` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_officers`
--

INSERT INTO `organization_officers` (`id`, `application_id`, `position`, `name`, `email`, `phone`, `student_id`, `created_at`, `updated_at`) VALUES
(5, 5, 'President', 'Deanne Pandes', 'depandes@my.cspc.edu.ph', '09514678765', '231002345', '2025-11-25 21:08:52', '2025-11-25 21:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `department` enum('ccs','cea','cthbm','chs','ctde','cas','gs') NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` tinyint(1) UNSIGNED NOT NULL,
  `in_organization` enum('yes','no') NOT NULL DEFAULT 'no',
  `organization_name` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `student_id`, `department`, `course`, `year_level`, `in_organization`, `organization_name`, `created_at`, `updated_at`) VALUES
(1, 1, '231002012', 'ccs', 'bsit', 3, 'no', NULL, '2025-11-25 02:39:48', '2025-11-25 02:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','organization') NOT NULL DEFAULT 'student',
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `email_verified` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `is_active`, `email_verified`, `created_at`, `updated_at`) VALUES
(1, 'irespeleta@my.cspc.edu.ph', '$2y$10$GCRbwqnkYOuM89TCeH5hH.XnxF.8YiLhyO6MrLW.gcaeOoGjgEO3G', 'student', 1, 0, '2025-11-25 02:39:48', '2025-11-25 18:42:45'),
(3, 'marhernandez@my.cspc.edu.ph', '$2y$10$z.LikCEy.oWOayVgXMfgseWHfd.gdvh.uBrBdYJmin6MqM.XqG7uC', 'organization', 1, 0, '2025-11-25 21:09:06', '2025-11-25 21:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `birthday` date NOT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `firstname`, `middlename`, `lastname`, `birthday`, `gender`, `phone`, `address_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'irene', 'buendia', 'espeleta', '2025-11-25', 'male', '09512736322', 1, '2025-11-25 02:39:48', '2025-11-25 02:39:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_region` (`region`),
  ADD KEY `idx_city` (`city_municipality`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_organization_name` (`organization_name`),
  ADD UNIQUE KEY `unique_acronym` (`organization_acronym`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_organization_type` (`organization_type`),
  ADD KEY `idx_organization_category` (`organization_category`);

--
-- Indexes for table `organization_advisors`
--
ALTER TABLE `organization_advisors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_department` (`department`);

--
-- Indexes for table `organization_applications`
--
ALTER TABLE `organization_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_submitted_at` (`submitted_at`),
  ADD KEY `idx_organization_name` (`organization_name`);

--
-- Indexes for table `organization_files`
--
ALTER TABLE `organization_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_file_type` (`file_type`);

--
-- Indexes for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_student_id` (`student_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_id` (`student_id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_department` (`department`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_address_id` (`address_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `organization_advisors`
--
ALTER TABLE `organization_advisors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organization_applications`
--
ALTER TABLE `organization_applications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `organization_files`
--
ALTER TABLE `organization_files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `organization_officers`
--
ALTER TABLE `organization_officers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `fk_organizations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `organization_advisors`
--
ALTER TABLE `organization_advisors`
  ADD CONSTRAINT `fk_organization_advisors_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_files`
--
ALTER TABLE `organization_files`
  ADD CONSTRAINT `fk_organization_files_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD CONSTRAINT `fk_organization_officers_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
