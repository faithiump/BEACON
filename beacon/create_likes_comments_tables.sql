-- =====================================================
-- Table: post_likes
-- Description: Stores likes on posts (announcements and events)
-- =====================================================
CREATE TABLE IF NOT EXISTS `post_likes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT(11) UNSIGNED NOT NULL,
    `post_type` ENUM('announcement', 'event') NOT NULL,
    `post_id` INT(11) UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_student_post` (`student_id`, `post_type`, `post_id`),
    KEY `idx_post` (`post_type`, `post_id`),
    CONSTRAINT `fk_likes_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: post_comments
-- Description: Stores comments on posts (announcements and events)
-- =====================================================
CREATE TABLE IF NOT EXISTS `post_comments` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT(11) UNSIGNED NOT NULL,
    `post_type` ENUM('announcement', 'event') NOT NULL,
    `post_id` INT(11) UNSIGNED NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_post` (`post_type`, `post_id`),
    KEY `idx_student` (`student_id`),
    CONSTRAINT `fk_comments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

