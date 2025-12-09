-- =====================================================
-- Add views column to events table
-- Description: Track how many times an event has been viewed
-- =====================================================

ALTER TABLE `events`
ADD COLUMN `views` int(11) UNSIGNED NOT NULL DEFAULT 0 AFTER `current_attendees`;

