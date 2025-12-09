-- =====================================================
-- Create admin_notification_views table
-- Description: Tracks which organization application notifications have been viewed by which admin
-- =====================================================

CREATE TABLE IF NOT EXISTS `admin_notification_views` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) UNSIGNED NOT NULL,
  `application_id` int(11) UNSIGNED NOT NULL,
  `viewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_admin_application` (`admin_id`, `application_id`),
  KEY `idx_admin_id` (`admin_id`),
  KEY `idx_application_id` (`application_id`),
  CONSTRAINT `fk_admin_notification_views_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_admin_notification_views_application` FOREIGN KEY (`application_id`) REFERENCES `organization_applications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

