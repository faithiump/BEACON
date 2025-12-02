-- =====================================================
-- Update events table status ENUM to include 'ongoing' and 'ended'
-- Description: Adds 'ongoing' and 'ended' status values to the events table
-- =====================================================

ALTER TABLE `events` 
MODIFY COLUMN `status` ENUM('upcoming','ongoing','ended','active','completed','cancelled') NOT NULL DEFAULT 'upcoming' COMMENT 'Current status of the event';



