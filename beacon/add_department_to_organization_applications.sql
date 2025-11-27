-- =====================================================
-- Add department column to organization_applications table
-- Description: Adds department field for organization applications
-- =====================================================

ALTER TABLE `organization_applications` 
ADD COLUMN `department` ENUM('ccs', 'cea', 'cthbm', 'chs', 'ctde', 'cas', 'gs') NULL DEFAULT NULL AFTER `organization_category`;

