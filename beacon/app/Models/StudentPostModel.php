<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentPostModel extends Model
{
    protected $table            = 'student_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'title',
        'content',
        'category',
        'image',
        'tags',
        'views',
        'is_pinned'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'student_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'category' => 'required|in_list[general,events,academics,marketplace,help]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all posts with student information
     */
    public function getAllPosts($category = null, $limit = null, $offset = 0)
    {
        $builder = $this->db->table($this->table);
        $builder->select('student_posts.*, 
                         students.student_id as student_number,
                         user_profiles.firstname, 
                         user_profiles.lastname,
                         user_photos.photo_path');
        $builder->join('students', 'students.id = student_posts.student_id');
        $builder->join('user_profiles', 'user_profiles.user_id = students.user_id', 'left');
        $builder->join('user_photos', 'user_photos.user_id = students.user_id', 'left');
        
        if ($category && $category !== 'all') {
            $builder->where('student_posts.category', $category);
        }
        
        $builder->orderBy('student_posts.is_pinned', 'DESC');
        $builder->orderBy('student_posts.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get post by ID with student information
     */
    public function getPostById($postId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('student_posts.*, 
                         students.student_id as student_number,
                         user_profiles.firstname, 
                         user_profiles.lastname,
                         user_photos.photo_path');
        $builder->join('students', 'students.id = student_posts.student_id');
        $builder->join('user_profiles', 'user_profiles.user_id = students.user_id', 'left');
        $builder->join('user_photos', 'user_photos.user_id = students.user_id', 'left');
        $builder->where('student_posts.id', $postId);
        
        return $builder->get()->getRowArray();
    }

    /**
     * Increment view count
     */
    public function incrementViews($postId)
    {
        $this->db->table($this->table)
            ->where('id', $postId)
            ->set('views', 'views + 1', false)
            ->update();
    }

    /**
     * Get posts by student ID
     */
    public function getPostsByStudent($studentId, $limit = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where('student_id', $studentId);
        $builder->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }
}

