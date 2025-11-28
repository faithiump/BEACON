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
        'organization_id',
        'commenter_type',
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
     * Format a single comment with user info (supports both students and organizations)
     */
    private function formatComment($comment)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('post_comments');
        
        // Check if it's an organization comment
        if (isset($comment['commenter_type']) && $comment['commenter_type'] === 'organization' && 
            $comment['organization_id'] !== null && $comment['organization_id'] > 0) {
            // Organization comment
            // Try to get organization photo from user_photos table via organizations.user_id
            $builder->select('post_comments.*, organizations.organization_name as org_name, organizations.organization_acronym as org_acronym, organizations.user_id as org_user_id, user_photos.photo_path as org_photo');
            $builder->join('organizations', 'organizations.id = post_comments.organization_id');
            $builder->join('user_photos', 'user_photos.user_id = organizations.user_id', 'left');
            $builder->where('post_comments.id', $comment['id']);
            $orgComment = $builder->get()->getRowArray();
            
            if ($orgComment) {
                return [
                    'id' => $orgComment['id'],
                    'student_id' => null,
                    'organization_id' => $orgComment['organization_id'],
                    'commenter_type' => 'organization',
                    'post_type' => $orgComment['post_type'],
                    'post_id' => $orgComment['post_id'],
                    'parent_comment_id' => $orgComment['parent_comment_id'] ?? null,
                    'content' => $orgComment['content'],
                    'created_at' => $orgComment['created_at'],
                    'updated_at' => $orgComment['updated_at'] ?? null,
                    'firstname' => $orgComment['org_name'] ?? 'Organization',
                    'lastname' => '',
                    'is_organization' => true,
                    'org_name' => $orgComment['org_name'] ?? '',
                    'org_acronym' => $orgComment['org_acronym'] ?? '',
                    'org_photo' => !empty($orgComment['org_photo']) ? base_url($orgComment['org_photo']) : null
                ];
            }
        } else if ($comment['student_id'] !== null && $comment['student_id'] > 0) {
            // Student comment
            $builder->select('post_comments.*, user_profiles.firstname, user_profiles.lastname, students.student_id');
            $builder->join('students', 'students.id = post_comments.student_id');
            $builder->join('user_profiles', 'user_profiles.user_id = students.user_id');
            $builder->where('post_comments.id', $comment['id']);
            $studentComment = $builder->get()->getRowArray();
            
            if ($studentComment) {
                $studentComment['is_organization'] = false;
                $studentComment['commenter_type'] = 'student';
                return $studentComment;
            }
        }
        
        // Fallback: return raw comment if formatting fails
        $comment['is_organization'] = isset($comment['commenter_type']) && $comment['commenter_type'] === 'organization';
        
        // Ensure required fields exist
        if (!isset($comment['firstname'])) {
            if ($comment['is_organization']) {
                $comment['firstname'] = 'Organization';
            } else {
                $comment['firstname'] = 'User';
            }
        }
        $comment['lastname'] = $comment['lastname'] ?? '';
        
        // Ensure all required fields are set
        $comment['id'] = $comment['id'] ?? 0;
        $comment['content'] = $comment['content'] ?? '';
        $comment['created_at'] = $comment['created_at'] ?? date('Y-m-d H:i:s');
        $comment['student_id'] = $comment['student_id'] ?? null;
        $comment['organization_id'] = $comment['organization_id'] ?? null;
        $comment['commenter_type'] = $comment['commenter_type'] ?? 'student';
        
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


