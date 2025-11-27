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
     * Get comments for a post
     */
    public function getComments($postType, $postId, $limit = 50)
    {
        return $this->select('post_comments.*, user_profiles.firstname, user_profiles.lastname, students.student_id')
                    ->join('students', 'students.id = post_comments.student_id')
                    ->join('user_profiles', 'user_profiles.user_id = students.user_id')
                    ->where('post_comments.post_type', $postType)
                    ->where('post_comments.post_id', $postId)
                    ->orderBy('post_comments.created_at', 'ASC')
                    ->limit($limit)
                    ->findAll();
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


