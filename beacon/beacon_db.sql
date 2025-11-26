-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 01:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
(1, 'Region 5', 'CMA SUR', 'ICE', '2025-11-25 01:55:10', '2025-11-25 01:55:10'),
(2, 'Region 5', 'Cam Sur', 'Bato', '2025-11-25 08:06:25', '2025-11-25 08:06:25'),
(3, 'REG 5', 'BULAAAA', 'EWAN Q', '2025-11-25 16:18:35', '2025-11-25 16:18:35');

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
(1, 2, 'BUANG NA Q', 'wala alanggg', 'high', 0, 0, '2025-11-25 15:39:07', '2025-11-25 15:39:07');

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

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `org_id`, `org_type`, `event_name`, `description`, `date`, `time`, `venue`, `max_attendees`, `current_attendees`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'academic', 'CSPC SIKLAB NIGHT', 'wala alang', '2025-11-25', '20:33:00', 'CSPC GYM', NULL, 0, '1764084812_207d4cb75a36a81d7de4.png', 'upcoming', '2025-11-25 15:33:32', '2025-11-25 15:33:32');

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
(1, 4, 'Junior Philippine Computer Society', 'JPCS', 'academic', 'departmental', '2017-02-08', 'The mission of JPCS is to establish an extracurricular learning environment that: ', 'The JPCS envisions becoming a platform for sharing knowledge, pursuing goals, and fostering collaboration among ICT professionals and enthusiasts. The organization aims to unite young people through leadership, technical skill, and ethical behavior, building enduring connections, cooperation, and a dedication to faith and country. ', 'Facilitate information sharing among members to advance ICT nationwide.', 'marihernandez@my.cspc.edu.ph', '09123456789', 12, 1, '2025-11-25 17:29:15', '2025-11-25 17:29:15'),
(2, 1, 'Supreme Student Council', 'SSC', 'academic', 'university_wide', '2019-01-09', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.ytfdd', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.errfds', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.erfwsdvvb', 'mariel005hernandez@gmail.com', '12345678901', 1, 1, '2025-11-25 21:57:06', '2025-11-25 17:27:06'),
(3, 2, 'Computer Science Society', 'CSS', 'academic', 'departmental', '2025-11-25', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.dddds', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.dfewedss', 'css@cspc.edu.ph', '+639514501937', 15, 1, '2025-11-25 21:57:10', '2025-11-25 17:19:14');

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
(1, 1, 'Dr. Juan Dela Cruz', 'irespeleta@my.cspc.edu.ph', '09123456790', 'ccs', '2025-11-25 17:28:27', '2025-11-25 17:28:27'),
(2, 2, 'Sir Bins', 'uan.delacruz@cspc.edu.ph', '09123456790', 'ccs', '2025-11-25 17:41:14', '2025-11-25 17:41:14'),
(3, 3, 'dfghgffrf', 'ggt@gmail.com', '12345678901', 'ccs', '2025-11-25 21:56:20', '2025-11-25 21:56:20');

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

INSERT INTO `organization_applications` (`id`, `organization_name`, `organization_acronym`, `organization_type`, `organization_category`, `founding_date`, `mission`, `vision`, `objectives`, `contact_email`, `password_hash`, `contact_phone`, `current_members`, `status`, `admin_notes`, `reviewed_by`, `reviewed_at`, `submitted_at`, `created_at`, `updated_at`) VALUES
(1, 'Junior Philippine Computer Society', 'JPCS', 'academic', 'departmental', '2017-02-08', 'The mission of JPCS is to establish an extracurricular learning environment that: ', 'The JPCS envisions becoming a platform for sharing knowledge, pursuing goals, and fostering collaboration among ICT professionals and enthusiasts. The organization aims to unite young people through leadership, technical skill, and ethical behavior, building enduring connections, cooperation, and a dedication to faith and country. ', 'Facilitate information sharing among members to advance ICT nationwide.', 'marihernandez@my.cspc.edu.ph', '12345678', '09123456789', 12, 'approved', NULL, 1, '2025-11-25 09:29:15', '2025-11-25 09:28:27', '2025-11-25 17:28:27', '2025-11-25 21:51:52'),
(2, 'Computer Science Society', 'CSS', 'academic', 'departmental', '2025-11-25', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.dddds', 'School vision statements outline a school\'s values and objectives. They provide parents and the community a brief but clear overview of the overall ethos of the school. On the other hand, school mission statements explain what the school is currently doing to achieve its vision.dfewedss', 'css@cspc.edu.ph', '12345678', '+639514501937', 13, 'approved', NULL, 1, '2025-11-25 13:57:10', '2025-11-25 09:41:14', '2025-11-25 17:41:14', '2025-11-26 01:55:07'),
(3, 'Supreme Student Council', 'SSC', 'academic', 'university_wide', '2019-01-09', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.ytfdd', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.errfds', 'How to Design Data Definitions (HtDD) This web page summarizes the process of developing a data definition, with a particular focus on the interaction between the different shapes of domain information, program data, test cases and templates.erfwsdvvb', 'mariel005hernandez@gmail.com', '$2y$10$DY5MhiPIAyY/vIAv2oNW1eCiEMwthgfjm1AljRsf22ThGQzxHlgPm', '12345678901', 12, 'approved', NULL, 1, '2025-11-25 13:57:06', '2025-11-25 13:56:20', '2025-11-25 21:56:20', '2025-11-25 21:57:06');

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
(1, 1, 'constitution', 'Same day.docx', 'uploads/organizations/1764062907_cd8d58606ce94bcc8f88.docx', 13907, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '2025-11-25 17:28:27'),
(2, 1, 'certification', 'user.png', 'uploads/organizations/1764062907_c8a1b15e072f81b3dc21.png', 12344, 'image/png', '2025-11-25 17:28:27'),
(3, 2, 'constitution', 'Same day.docx', 'uploads/organizations/1764063674_dc41de44b5cd9b4e7cc3.docx', 13907, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '2025-11-25 17:41:14'),
(4, 2, 'certification', 'lg2.png', 'uploads/organizations/1764063674_96e52111928e96af0904.png', 195124, 'image/png', '2025-11-25 17:41:14'),
(5, 3, 'constitution', 'Same day.docx', 'uploads/organizations/1764078980_4c5023a3f5b67f5bb699.docx', 13907, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '2025-11-25 21:56:20'),
(6, 3, 'certification', '9104f108-3908-4adf-9c10-fa6dde183428.jpg', 'uploads/organizations/1764078980_c51988ef80035e7b1fe4.jpg', 202329, 'image/jpeg', '2025-11-25 21:56:20');

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
(1, 1, 'President', 'Irene Espeleta', 'irespeleta@my.cspc.edu.ph', '09123456791', '2310030927', '2025-11-25 17:28:27', '2025-11-25 17:28:27'),
(2, 2, 'Officer', 'Jane Smith', 'jane.smith@student.cspc.edu.ph', '09123456791', '2310030927', '2025-11-25 17:41:14', '2025-11-25 17:41:14'),
(3, 3, 'Leader', 'HHSS', 'wer@gmail.com', '123456789089', '234567890', '2025-11-25 21:56:20', '2025-11-25 21:56:20');

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

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `org_id`, `product_name`, `description`, `price`, `stock`, `sold`, `sizes`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'CSPC Lanyard', 'bastaa', 200.00, 21, 0, 'S', '1764086381_f2a6e047c988a93b8c36.jpg', 'available', '2025-11-25 15:59:42', '2025-11-25 15:59:42'),
(2, 2, 'CSPC LOGO', 'wehhh', 23.00, 100, 0, 'S', '1764086572_ccc97d5a0dc030cccc99.png', 'available', '2025-11-25 16:02:52', '2025-11-25 16:02:52');

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
(1, 1, '231003029', 'ccs', 'bsit', 3, 'no', NULL, '2025-11-25 01:55:10', '2025-11-25 01:55:10'),
(2, 3, '231003025', 'cea', 'bsece', 3, 'yes', 'Computer Science Society', '2025-11-25 08:06:25', '2025-11-25 08:06:25'),
(3, 7, '23100302020', 'ccs', 'bsit', 3, 'yes', 'Computer Science Society', '2025-11-25 16:18:35', '2025-11-25 17:10:24');

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

--
-- Dumping data for table `student_organization_memberships`
--

INSERT INTO `student_organization_memberships` (`id`, `student_id`, `org_id`, `status`, `joined_at`, `updated_at`) VALUES
(1, 3, 3, 'active', '2025-11-25 17:19:14', '2025-11-25 17:19:14'),
(2, 3, 2, 'active', '2025-11-25 17:26:38', '2025-11-25 17:27:06'),
(3, 3, 1, 'pending', '2025-11-25 17:53:26', '2025-11-25 17:53:26');

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
(1, 'mariel005hernandez@gmail.com', '$2y$10$b4VO7WnAuiWKo4SG9ksLPOc6nqwfhZWxFUhQZM1lE8.D0Fh.SE3cq', 'organization', 1, 0, '2025-11-25 01:55:10', '2025-11-25 21:57:06'),
(2, 'css@cspc.edu.ph', '$2y$10$j7RHPgdLC8Of7iop482sfOiJ/ghREjuz9XoawVGMbtz8I/z/EPFCe', 'organization', 1, 0, '2025-11-25 11:54:17', '2025-11-25 11:54:17'),
(3, 'irespeleta@my.cspc.edu.ph', '$2y$10$SoengvshrLCgfWMwC8QyHuhLVT4pS/cEKhxpFASK307C..AsJm1/.', 'student', 1, 0, '2025-11-25 08:06:25', '2025-11-25 08:06:25'),
(4, 'marihernandez@my.cspc.edu.ph', '$2y$10$dixPOMt6Bx2ZZrGdgBP6pefFZarV.UNjfriVHiRgs7PveNLs4Q2Qi', 'organization', 1, 0, '2025-11-25 16:26:50', '2025-11-25 16:26:50'),
(7, 'dean@gmail.com', '$2y$10$Bh69V3moiy9KDSpe3Fk2Duix8DmgWD9SOgdzZLWoFyYJYGBV/1Xam', 'student', 1, 0, '2025-11-25 16:18:35', '2025-11-25 16:18:35');

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
(1, 1, 'Mayen', 'Barrios', 'Hernandez', '2025-11-25', 'female', '09514501937', 1, '2025-11-25 01:55:10', '2025-11-25 01:55:10'),
(2, 3, 'Irene', 'Barrios', 'Espeleta', '2025-11-03', 'female', '09514501937', 2, '2025-11-25 08:06:25', '2025-11-25 08:06:25'),
(3, 7, 'Deanne', 'Faith', 'Pandez', '2025-11-26', 'female', '12345678901', 3, '2025-11-25 16:18:35', '2025-11-25 16:18:35');

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
-- Indexes for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_application_id` (`application_id`),
  ADD KEY `idx_student_id` (`student_id`);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organization_advisors`
--
ALTER TABLE `organization_advisors`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organization_applications`
--
ALTER TABLE `organization_applications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `organization_files`
--
ALTER TABLE `organization_files`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `organization_officers`
--
ALTER TABLE `organization_officers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_organization_memberships`
--
ALTER TABLE `student_organization_memberships`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `organization_officers`
--
ALTER TABLE `organization_officers`
  ADD CONSTRAINT `fk_organization_officers_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_profiles_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
