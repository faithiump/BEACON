-- =====================================================
-- Create event_attendees table
-- Description: Tracks which students have joined which events
-- =====================================================

CREATE TABLE IF NOT EXISTS `event_attendees` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to events table',
  `student_id` int(11) UNSIGNED NOT NULL COMMENT 'Foreign key to students table',
  `joined_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'When the student joined the event',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_event_student` (`event_id`, `student_id`),
  KEY `idx_event_id` (`event_id`),
  KEY `idx_student_id` (`student_id`),
  CONSTRAINT `fk_event_attendees_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_event_attendees_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

