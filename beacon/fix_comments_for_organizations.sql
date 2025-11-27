-- =====================================================
-- Fix post_comments table to allow organization comments
-- Description: Makes student_id nullable so organizations can comment
-- =====================================================

-- First, drop the foreign key constraint
ALTER TABLE `post_comments` 
DROP FOREIGN KEY `fk_comments_student`;

-- Make student_id nullable
ALTER TABLE `post_comments` 
MODIFY COLUMN `student_id` INT(11) UNSIGNED NULL DEFAULT NULL;

-- Re-add the foreign key constraint with ON DELETE SET NULL for organization comments
ALTER TABLE `post_comments` 
ADD CONSTRAINT `fk_comments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

