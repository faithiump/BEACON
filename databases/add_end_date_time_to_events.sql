-- =====================================================
-- Add end_date and end_time columns to events table
-- Description: Stores the end date and time for events
-- =====================================================

ALTER TABLE `events`
ADD COLUMN `end_date` DATE NULL AFTER `date`,
ADD COLUMN `end_time` TIME NULL AFTER `time`;







