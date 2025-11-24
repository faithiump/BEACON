-- =====================================================
-- BEACON Database Tables - Normalized Schema
-- Database: beacon_db
-- =====================================================
-- Instructions:
-- 1. Open phpMyAdmin
-- 2. Select the 'beacon_db' database
-- 3. Go to the SQL tab
-- 4. Copy and paste this entire script
-- 5. Click "Go" to execute
-- =====================================================

-- =====================================================
-- Table: users
-- Description: Core user authentication table
-- Purpose: Stores login credentials and basic account info
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('student', 'organization') NOT NULL DEFAULT 'student',
    `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `email_verified` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_email` (`email`),
    KEY `idx_role` (`role`),
    KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: addresses
-- Description: Address information
-- Purpose: Reusable address table to avoid redundancy
-- =====================================================
CREATE TABLE IF NOT EXISTS `addresses` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `region` VARCHAR(100) NOT NULL,
    `city_municipality` VARCHAR(100) NOT NULL,
    `barangay` VARCHAR(100) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_region` (`region`),
    KEY `idx_city` (`city_municipality`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: user_profiles
-- Description: Extended user profile information
-- Purpose: Stores personal information linked to users
-- =====================================================
CREATE TABLE IF NOT EXISTS `user_profiles` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `firstname` VARCHAR(100) NOT NULL,
    `middlename` VARCHAR(100) DEFAULT NULL,
    `lastname` VARCHAR(100) NOT NULL,
    `birthday` DATE NOT NULL,
    `gender` ENUM('male', 'female', 'other', 'prefer_not_to_say') NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `address_id` INT(11) UNSIGNED DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_id` (`user_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_address_id` (`address_id`),
    CONSTRAINT `fk_user_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_profiles_address` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: students
-- Description: Student-specific information
-- Purpose: Stores academic information for student users
-- =====================================================
CREATE TABLE IF NOT EXISTS `students` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `student_id` VARCHAR(50) NOT NULL,
    `department` ENUM('ccs', 'cea', 'cthbm', 'chs', 'ctde', 'cas', 'gs') NOT NULL,
    `course` VARCHAR(100) NOT NULL,
    `year_level` TINYINT(1) UNSIGNED NOT NULL,
    `in_organization` ENUM('yes', 'no') NOT NULL DEFAULT 'no',
    `organization_name` VARCHAR(200) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_student_id` (`student_id`),
    UNIQUE KEY `unique_user_id` (`user_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_student_id` (`student_id`),
    KEY `idx_department` (`department`),
    CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: organizations
-- Description: Approved organizations
-- Purpose: Stores information about approved/active organizations
-- =====================================================
CREATE TABLE IF NOT EXISTS `organizations` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED DEFAULT NULL,
    `organization_name` VARCHAR(200) NOT NULL,
    `organization_acronym` VARCHAR(20) NOT NULL,
    `organization_type` ENUM('academic', 'non_academic', 'service', 'religious', 'cultural', 'sports', 'other') NOT NULL,
    `organization_category` ENUM('departmental', 'inter_departmental', 'university_wide') NOT NULL,
    `founding_date` DATE NOT NULL,
    `mission` TEXT NOT NULL,
    `vision` TEXT NOT NULL,
    `objectives` TEXT NOT NULL,
    `contact_email` VARCHAR(255) NOT NULL,
    `contact_phone` VARCHAR(20) NOT NULL,
    `current_members` INT(11) UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_organization_name` (`organization_name`),
    UNIQUE KEY `unique_acronym` (`organization_acronym`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_organization_type` (`organization_type`),
    KEY `idx_organization_category` (`organization_category`),
    CONSTRAINT `fk_organizations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: organization_applications
-- Description: Organization launch applications
-- Purpose: Stores pending/approved/rejected organization applications
-- =====================================================
CREATE TABLE IF NOT EXISTS `organization_applications` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `organization_name` VARCHAR(200) NOT NULL,
    `organization_acronym` VARCHAR(20) NOT NULL,
    `organization_type` ENUM('academic', 'non_academic', 'service', 'religious', 'cultural', 'sports', 'other') NOT NULL,
    `organization_category` ENUM('departmental', 'inter_departmental', 'university_wide') NOT NULL,
    `founding_date` DATE NOT NULL,
    `mission` TEXT NOT NULL,
    `vision` TEXT NOT NULL,
    `objectives` TEXT NOT NULL,
    `contact_email` VARCHAR(255) NOT NULL,
    `contact_phone` VARCHAR(20) NOT NULL,
    `current_members` INT(11) UNSIGNED NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT DEFAULT NULL,
    `reviewed_by` INT(11) UNSIGNED DEFAULT NULL,
    `reviewed_at` DATETIME DEFAULT NULL,
    `submitted_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_status` (`status`),
    KEY `idx_submitted_at` (`submitted_at`),
    KEY `idx_organization_name` (`organization_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: organization_advisors
-- Description: Faculty advisors for organizations
-- Purpose: Stores advisor information for organization applications
-- =====================================================
CREATE TABLE IF NOT EXISTS `organization_advisors` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `department` ENUM('ccs', 'cea', 'cthbm', 'chs', 'ctde', 'cas', 'gs') NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_application_id` (`application_id`),
    KEY `idx_department` (`department`),
    CONSTRAINT `fk_organization_advisors_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: organization_officers
-- Description: Organization officers
-- Purpose: Stores officer information for organization applications
-- =====================================================
CREATE TABLE IF NOT EXISTS `organization_officers` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_id` INT(11) UNSIGNED NOT NULL,
    `position` VARCHAR(50) NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `student_id` VARCHAR(50) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_application_id` (`application_id`),
    KEY `idx_student_id` (`student_id`),
    CONSTRAINT `fk_organization_officers_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: organization_files
-- Description: File uploads for organization applications
-- Purpose: Stores references to uploaded files
-- =====================================================
CREATE TABLE IF NOT EXISTS `organization_files` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `application_id` INT(11) UNSIGNED NOT NULL,
    `file_type` ENUM('constitution', 'certification') NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_path` VARCHAR(500) NOT NULL,
    `file_size` INT(11) UNSIGNED DEFAULT NULL,
    `mime_type` VARCHAR(100) DEFAULT NULL,
    `uploaded_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    KEY `idx_application_id` (`application_id`),
    KEY `idx_file_type` (`file_type`),
    CONSTRAINT `fk_organization_files_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Notes on Normalization:
-- =====================================================
-- 1. Users table: Only authentication data (email, password, role)
-- 2. User_profiles: Personal information separated from auth
-- 3. Addresses: Reusable address table (can be shared)
-- 4. Students: Student-specific data separated
-- 5. Organizations: Approved organizations only
-- 6. Organization_applications: Separate table for applications
-- 7. Organization_advisors: One-to-one with applications
-- 8. Organization_officers: One-to-many with applications (can have multiple officers)
-- 9. Organization_files: One-to-many with applications (multiple files)
-- 
-- Benefits:
-- - No data redundancy
-- - Easier to maintain
-- - Better data integrity with foreign keys
-- - Flexible for future expansion
-- =====================================================

