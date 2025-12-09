-- =====================================================
-- Add audience controls to events table
-- Description: Adds columns to define who can see/join an event
-- =====================================================

ALTER TABLE `events`
ADD COLUMN `audience_type` ENUM('all','department','specific_students') NOT NULL DEFAULT 'all' AFTER `venue`,
ADD COLUMN `department_access` ENUM('ccs','cea','cthbm','chs','ctde','cas','gs') NULL AFTER `audience_type`,
ADD COLUMN `student_access` JSON NULL AFTER `department_access`;

