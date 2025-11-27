-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2025 at 09:38 AM
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
  `province` varchar(100) NOT NULL,
  `city_municipality` varchar(100) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `province`, `city_municipality`, `barangay`, `created_at`, `updated_at`) VALUES
(1, 'Region 5', 'CMA SUR', 'ICE', '2025-11-25 01:55:10', '2025-11-25 01:55:10'),
(2, 'Region 5', 'Cam Sur', 'Bato', '2025-11-25 08:06:25', '2025-11-25 08:06:25'),
(3, 'REG 5', 'BULAAAA', 'EWAN Q', '2025-11-25 16:18:35', '2025-11-25 16:18:35'),
(4, 'Roman', 'geds', 'masoli', '2025-11-26 12:28:42', '2025-11-26 16:55:54');

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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) UNSIGNED NOT NULL,
  `org_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `priority` enum('normal','high') NOT NULL DEFAULT 'normal',
  `views` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `is_pinned` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `org_id`, `title`, `content`, `priority`, `views`, `is_pinned`, `created_at`, `updated_at`) VALUES
(3, 7, 'stress week', 'stress  na si mariel', 'normal', 4, 0, '2025-11-27 08:07:26', '2025-11-27 08:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) UNSIGNED NOT NULL,
  `org_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to organizations table',
  `org_type` enum('academic','non_academic','service','religious','cultural','sports','other') NOT NULL COMMENT 'Type of organization hosting the event',
  `event_name` varchar(255) NOT NULL COMMENT 'Name/title of the event',
  `description` text NOT NULL COMMENT 'Detailed description of the event',
  `date` date NOT NULL COMMENT 'Event date',
  `time` time NOT NULL COMMENT 'Event time',
  `venue` varchar(255) NOT NULL COMMENT 'Location/venue where event will be held',
  `max_attendees` int(11) UNSIGNED DEFAULT NULL COMMENT 'Maximum number of attendees (NULL for unlimited)',
  `current_attendees` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Current number of registered attendees',
  `image` varchar(255) DEFAULT NULL COMMENT 'Event image filename',
  `status` enum('upcoming','active','completed','cancelled') NOT NULL DEFAULT 'upcoming' COMMENT 'Current status of the event',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `contact_phone` varchar(20) NOT NULL,
  `current_members` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `user_id`, `organization_name`, `organization_acronym`, `organization_type`, `organization_category`, `founding_date`, `mission`, `vision`, `objectives`, `contact_email`, `contact_phone`, `current_members`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 12, 'Junior Philippine Student Council', 'JPCS', 'academic', 'departmental', '2025-11-27', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'marhernandez@my.cspc.edu.ph', '09512741049', 0, 1, '2025-11-27 15:39:58', '2025-11-27 08:03:23');

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
(12, 6, 'Irene Espeleta', '', '12345678901', 'ccs', '2025-11-27 15:37:12', '2025-11-27 08:03:23');

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
  `department` enum('ccs','cea','cthbm','chs','ctde','cas','gs') DEFAULT NULL,
  `founding_date` date NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `objectives` text NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
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

INSERT INTO `organization_applications` (`id`, `organization_name`, `organization_acronym`, `organization_type`, `organization_category`, `department`, `founding_date`, `mission`, `vision`, `objectives`, `contact_email`, `password_hash`, `contact_phone`, `current_members`, `status`, `admin_notes`, `reviewed_by`, `reviewed_at`, `submitted_at`, `created_at`, `updated_at`) VALUES
(6, 'Junior Philippine Student Council', 'JPCS', 'academic', 'departmental', 'ccs', '2025-11-27', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'The mission of our organization is to create a supportive and engaging environment where students can grow, connect, and discover their full potential. We aim to empower every member by offering meaningful programs, activities, and opportunities that build confidence, leadership, and a strong sense of community. Through collaboration and service, we strive to make a positive impact both within the campus and beyond.', 'marhernandez@my.cspc.edu.ph', '$2y$10$OACU9Nam4G8kT5THBecXmOybSvtg.SWNWnaRygu2aVK9pu6Mg1Ody', '09512741049', 5, 'approved', NULL, 1, '2025-11-27 07:39:58', '2025-11-27 07:37:12', '2025-11-27 15:37:12', '2025-11-27 15:39:58');

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
(11, 6, 'constitution', '404 Not Founders_MP.pdf', 'uploads/organizations/1764229032_589f5b1c8e470cd6ee92.pdf', 1012490, 'application/pdf', '2025-11-27 15:37:12'),
(12, 6, 'certification', '496514889_1802230327314412_1875854150619998547_n.jpg', 'uploads/organizations/1764229032_a52ad2166ef33f6121cc.jpg', 49257, 'image/jpeg', '2025-11-27 15:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `organization_follows`
--

CREATE TABLE `organization_follows` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `org_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_follows`
--

INSERT INTO `organization_follows` (`id`, `student_id`, `org_id`, `created_at`, `updated_at`) VALUES
(8, 3, 7, '2025-11-27 07:39:58', '2025-11-27 07:39:58'),
(9, 4, 7, '2025-11-27 07:39:58', '2025-11-27 07:39:58');

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
(6, 6, 'President', 'Deanne Pandes', '', '45453234567', '2310020234', '2025-11-27 15:37:12', '2025-11-27 08:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED DEFAULT NULL,
  `post_type` enum('announcement','event') NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `parent_comment_id` int(11) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_comments`
--

INSERT INTO `post_comments` (`id`, `student_id`, `post_type`, `post_id`, `parent_comment_id`, `content`, `created_at`, `updated_at`) VALUES
(11, 4, 'announcement', 3, NULL, 'yoohhh', '2025-11-27 08:33:33', '2025-11-27 08:33:33'),
(12, NULL, 'announcement', 3, 11, '[ORG] Junior Philippine Student Council: yeaahhh', '2025-11-27 08:34:23', '2025-11-27 08:34:23'),
(13, 4, 'announcement', 3, 12, ',m.m,.m,', '2025-11-27 08:35:15', '2025-11-27 08:35:15');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `post_type` enum('announcement','event') NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `reaction_type` enum('like','love','care','haha','wow','sad','angry') NOT NULL DEFAULT 'like',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `student_id`, `post_type`, `post_id`, `reaction_type`, `created_at`) VALUES
(7, 4, 'announcement', 3, 'haha', '2025-11-27 08:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) UNSIGNED NOT NULL,
  `org_id` int(11) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `sold` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `sizes` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('available','low_stock','out_of_stock') NOT NULL DEFAULT 'available',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, 7, '23100302020', 'ccs', 'bsit', 3, 'yes', 'Computer Science Society', '2025-11-25 16:18:35', '2025-11-25 17:10:24'),
(4, 8, '231002012', 'ccs', 'bsit', 3, 'no', '', '2025-11-26 12:28:42', '2025-11-26 16:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `student_organization_memberships`
--

CREATE TABLE `student_organization_memberships` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `org_id` int(11) UNSIGNED NOT NULL,
  `status` enum('active','pending','inactive') NOT NULL DEFAULT 'active',
  `joined_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(7, 'dean@gmail.com', '$2y$10$Bh69V3moiy9KDSpe3Fk2Duix8DmgWD9SOgdzZLWoFyYJYGBV/1Xam', 'student', 1, 0, '2025-11-25 16:18:35', '2025-11-25 16:18:35'),
(8, 'irespeleta@my.cspc.edu.ph', '$2y$10$hiNpxHLXO3gXfukJai8tse24hdrOAIuFwudTc0w.vtowPROZa2jMe', 'student', 1, 0, '2025-11-26 12:28:42', '2025-11-26 12:28:42'),
(12, 'marhernandez@my.cspc.edu.ph', '$2y$10$OACU9Nam4G8kT5THBecXmOybSvtg.SWNWnaRygu2aVK9pu6Mg1Ody', 'organization', 1, 0, '2025-11-27 15:39:58', '2025-11-27 15:39:58');

-- --------------------------------------------------------

--
-- Table structure for table `user_photos`
--

CREATE TABLE `user_photos` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_photos`
--

INSERT INTO `user_photos` (`id`, `user_id`, `photo_path`, `created_at`, `updated_at`) VALUES
(3, 8, 'uploads/profiles/profile_8_1764176152.jpg', '2025-11-26 16:55:52', '2025-11-26 16:55:52'),
(7, 12, 'uploads/profiles/profile_12_1764229265.jpg', '2025-11-27 07:41:05', '2025-11-27 07:41:05');

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
(3, 7, 'Deanne', 'Faith', 'Pandez', '2025-11-26', 'female', '12345678901', 3, '2025-11-25 16:18:35', '2025-11-25 16:18:35'),
(4, 8, 'irene', 'buendia', 'espeleta', '2025-11-26', 'female', '09512736322', 4, '2025-11-26 12:28:42', '2025-11-26 16:55:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_region` (`province`),
  ADD KEY `idx_city` (`city_municipality`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `idx_org_id` (`org_id`),
  ADD KEY `idx_priority` (`priority`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_is_pinned` (`is_pinned`),
  ADD KEY `idx_org_created` (`org_id`,`created_at`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `idx_org_id` (`org_id`),
  ADD KEY `idx_date` (`date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_org_type` (`org_type`),
  ADD KEY `idx_event_name` (`event_name`),
  ADD KEY `idx_date_status` (`date`,`status`);

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
-- Indexes for table `organization_follows`
--
ALTER TABLE `organization_follows`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_org` (`student_id`,`org_id`),
  ADD KEY `fk_org_follows_org` (`org_id`);

--
-- Indexes for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_student_id` (`student_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post` (`post_type`,`post_id`),
  ADD KEY `idx_student` (`student_id`),
  ADD KEY `idx_parent` (`parent_comment_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_post` (`student_id`,`post_type`,`post_id`),
  ADD KEY `idx_post` (`post_type`,`post_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `idx_org_id` (`org_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_product_name` (`product_name`),
  ADD KEY `idx_org_status` (`org_id`,`status`);

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
-- Indexes for table `student_organization_memberships`
--
ALTER TABLE `student_organization_memberships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_org` (`student_id`,`org_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_org_id` (`org_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `organization_advisors`
--
ALTER TABLE `organization_advisors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_applications`
--
ALTER TABLE `organization_applications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `organization_files`
--
ALTER TABLE `organization_files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_follows`
--
ALTER TABLE `organization_follows`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `organization_officers`
--
ALTER TABLE `organization_officers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_organization_memberships`
--
ALTER TABLE `student_organization_memberships`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_photos`
--
ALTER TABLE `user_photos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_announcements_organization` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_organization` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `organization_follows`
--
ALTER TABLE `organization_follows`
  ADD CONSTRAINT `fk_org_follows_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_org_follows_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD CONSTRAINT `fk_organization_officers_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_comment_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_likes_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_organization` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_organization_memberships`
--
ALTER TABLE `student_organization_memberships`
  ADD CONSTRAINT `fk_memberships_organization` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_memberships_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_photos`
--
ALTER TABLE `user_photos`
  ADD CONSTRAINT `fk_user_photos_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
