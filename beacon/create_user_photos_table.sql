-- =====================================================
-- Create user_photos table
-- Description: Stores profile pictures for users (both students and organizations)
-- Purpose: Separate table for profile pictures with reference to users table
-- =====================================================

CREATE TABLE IF NOT EXISTS `user_photos` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `photo_path` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_user_id` (`user_id`),
    KEY `idx_user_id` (`user_id`),
    CONSTRAINT `fk_user_photos_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

