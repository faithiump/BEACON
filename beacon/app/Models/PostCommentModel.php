<?php

namespace App\Models;

use CodeIgniter\Model;

class PostCommentModel extends Model
{
    protected $table            = 'post_comments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'post_type',
        'post_id',
        'parent_comment_id',
        'content'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get comments for a post (including all nested replies)
     */
    public function getComments($postType, $postId, $limit = 50)
    {
        // Get top-level comments (no parent)
        $topLevelComments = $this->where('post_comments.post_type', $postType)
                    ->where('post_comments.post_id', $postId)
                    ->where('post_comments.parent_comment_id IS NULL')
                    ->orderBy('post_comments.created_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
        
        // Format comments with user info and all nested replies
        $formattedComments = [];
        foreach ($topLevelComments as $comment) {
            $formattedComment = $this->formatComment($comment);
            if ($formattedComment) {
                // Recursively get all replies (including nested replies)
                $formattedComment['replies'] = $this->getRepliesRecursive($comment['id']);
                $formattedComments[] = $formattedComment;
            }
        }
        
        return $formattedComments;
    }
    
    /**
     * Recursively get all replies for a comment
     */
    private function getRepliesRecursive($parentId)
    {
        // Get direct replies
        $replies = $this->where('parent_comment_id', $parentId)
            ->orderBy('created_at', 'ASC')
            ->findAll();
        
        $formattedReplies = [];
        foreach ($replies as $reply) {
            $formattedReply = $this->formatComment($reply);
            if ($formattedReply) {
                // Recursively get nested replies
                $formattedReply['replies'] = $this->getRepliesRecursive($reply['id']);
                $formattedReplies[] = $formattedReply;
            }
        }
        
        return $formattedReplies;
    }
    
    /**
     * Format a single comment with user info
     */
    private function formatComment($comment)
    {
        if ($comment['student_id'] !== null && $comment['student_id'] > 0) {
            // Student comment
            $db = \Config\Database::connect();
            $builder = $db->table('post_comments');
            $builder->select('post_comments.*, user_profiles.firstname, user_profiles.lastname, students.student_id');
            $builder->join('students', 'students.id = post_comments.student_id');
            $builder->join('user_profiles', 'user_profiles.user_id = students.user_id');
            $builder->where('post_comments.id', $comment['id']);
            $studentComment = $builder->get()->getRowArray();
            
            if ($studentComment) {
                $studentComment['is_organization'] = false;
                return $studentComment;
            }
        } else {
            // Organization comment (stored with student_id = null)
            // Content format: "[ORG] Organization Name: comment text"
            $content = $comment['content'] ?? '';
            if (strpos($content, '[ORG]') === 0) {
                $parts = explode(':', $content, 2);
                $orgName = str_replace('[ORG]', '', $parts[0]);
                $commentText = isset($parts[1]) ? trim($parts[1]) : $content;
                return [
                    'id' => $comment['id'],
                    'student_id' => null,
                    'post_type' => $comment['post_type'],
                    'post_id' => $comment['post_id'],
                    'parent_comment_id' => $comment['parent_comment_id'] ?? null,
                    'content' => $commentText,
                    'created_at' => $comment['created_at'],
                    'updated_at' => $comment['updated_at'],
                    'firstname' => trim($orgName),
                    'lastname' => '',
                    'is_organization' => true
                ];
            } else {
                // Fallback for comments without [ORG] prefix but with null student_id
                return [
                    'id' => $comment['id'],
                    'student_id' => null,
                    'post_type' => $comment['post_type'],
                    'post_id' => $comment['post_id'],
                    'parent_comment_id' => $comment['parent_comment_id'] ?? null,
                    'content' => $content,
                    'created_at' => $comment['created_at'],
                    'updated_at' => $comment['updated_at'] ?? null,
                    'firstname' => 'Organization',
                    'lastname' => '',
                    'is_organization' => true
                ];
            }
        }
        // Fallback: return raw comment if formatting fails
        $comment['is_organization'] = false;
        $comment['firstname'] = $comment['firstname'] ?? 'User';
        $comment['lastname'] = $comment['lastname'] ?? '';
        return $comment;
    }

    /**
     * Get comment count for a post
     */
    public function getCommentCount($postType, $postId)
    {
        return $this->where('post_type', $postType)
                    ->where('post_id', $postId)
                    ->countAllResults();
    }
}


