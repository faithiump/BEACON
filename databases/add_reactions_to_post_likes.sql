-- =====================================================
-- Add reaction_type column to post_likes table
-- Description: Adds support for Facebook-style reactions
-- =====================================================

-- Add reaction_type column (ignore error if column already exists)
ALTER TABLE `post_likes` 
ADD COLUMN `reaction_type` ENUM('like', 'love', 'care', 'haha', 'wow', 'sad', 'angry') NOT NULL DEFAULT 'like' AFTER `post_id`;

-- Update existing likes to have 'like' as default reaction type
UPDATE `post_likes` SET `reaction_type` = 'like' WHERE `reaction_type` IS NULL OR `reaction_type` = '';

