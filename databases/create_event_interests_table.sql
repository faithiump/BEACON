-- =====================================================
-- Create event_interests table
-- Description: Tracks which students are interested in which events
-- =====================================================

CREATE TABLE IF NOT EXISTS `event_interests` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to events table',
  `student_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to students table',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'When the student marked as interested',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_event_student` (`event_id`, `student_id`),
  KEY `idx_event_id` (`event_id`),
  KEY `idx_student_id` (`student_id`),
  CONSTRAINT `fk_event_interests_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_event_interests_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

