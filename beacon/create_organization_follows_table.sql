-- =====================================================
-- Table: organization_follows
-- Description: Stores student follows for organizations
-- Purpose: Allow students to follow organizations to see their events, activities, posts, etc.
-- =====================================================
CREATE TABLE IF NOT EXISTS `organization_follows` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT(11) UNSIGNED NOT NULL,
    `org_id` INT(11) UNSIGNED NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_student_org` (`student_id`, `org_id`),
    CONSTRAINT `fk_org_follows_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_org_follows_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

