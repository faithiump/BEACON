-- =====================================================
-- Add parent_comment_id to post_comments table
-- Description: Allows comments to be replies to other comments
-- =====================================================

ALTER TABLE `post_comments` 
ADD COLUMN `parent_comment_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `post_id`,
ADD KEY `idx_parent` (`parent_comment_id`),
ADD CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_comment_id`) REFERENCES `post_comments` (`id`) ON DELETE CASCADE;

