<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumPostModel extends Model
{
    protected $table            = 'forum_posts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'organization_id',
        'author_type',
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
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'category' => 'required|in_list[general,events,academics,marketplace,help]',
        'author_type' => 'required|in_list[student,organization]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all posts with author information (student or organization)
     */
    public function getAllPosts($category = null, $limit = null, $offset = 0, $filter = 'latest')
    {
        $builder = $this->db->table($this->table);
        $builder->select('forum_posts.*,
                         students.student_id as student_number,
                         students.user_id as student_user_id,
                         user_profiles.firstname, 
                         user_profiles.lastname,
                         user_photos.photo_path,
                         organizations.organization_name as org_name,
                         organizations.organization_acronym as org_acronym,
                         org_photos.photo_path as org_photo');
        
        // Left join for student data
        $builder->join('students', 'students.id = forum_posts.student_id', 'left');
        $builder->join('user_profiles', 'user_profiles.user_id = students.user_id', 'left');
        $builder->join('user_photos', 'user_photos.user_id = students.user_id', 'left');
        
        // Left join for organization data
        $builder->join('organizations', 'organizations.id = forum_posts.organization_id', 'left');
        $builder->join('user_photos as org_photos', 'org_photos.user_id = organizations.user_id', 'left');
        
        if ($category && $category !== 'all') {
            $builder->where('forum_posts.category', $category);
        }
        
        // Default ordering (filter will be handled after fetching)
        $builder->orderBy('forum_posts.is_pinned', 'DESC');
        $builder->orderBy('forum_posts.created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit, $offset);
        }
        
        $posts = $builder->get()->getResultArray();
        
        // Format posts to include author information
        foreach ($posts as &$post) {
            if ($post['author_type'] === 'student') {
                $post['author_name'] = trim(($post['firstname'] ?? '') . ' ' . ($post['lastname'] ?? ''));
                $post['author_photo'] = !empty($post['photo_path']) ? base_url($post['photo_path']) : null;
                $post['author_badge'] = 'student';
            } else {
                $post['author_name'] = $post['org_name'] ?? 'Organization';
                $post['author_photo'] = !empty($post['org_photo']) ? base_url($post['org_photo']) : null;
                $post['author_badge'] = 'organization';
                $post['firstname'] = $post['org_name'] ?? '';
                $post['lastname'] = '';
            }
        }
        
        // Handle sorting based on filter (after getting reaction counts in controller)
        // Note: Sorting by reaction count will be done in the controller after reaction counts are calculated
        
        return $posts;
    }

    /**
     * Get post by ID with author information
     */
    public function getPostById($postId)
    {
        $builder = $this->db->table($this->table);
        $builder->select('forum_posts.*,
                         students.student_id as student_number,
                         students.user_id as student_user_id,
                         user_profiles.firstname, 
                         user_profiles.lastname,
                         user_photos.photo_path,
                         organizations.organization_name as org_name,
                         organizations.organization_acronym as org_acronym,
                         org_photos.photo_path as org_photo');
        
        // Left join for student data
        $builder->join('students', 'students.id = forum_posts.student_id', 'left');
        $builder->join('user_profiles', 'user_profiles.user_id = students.user_id', 'left');
        $builder->join('user_photos', 'user_photos.user_id = students.user_id', 'left');
        
        // Left join for organization data
        $builder->join('organizations', 'organizations.id = forum_posts.organization_id', 'left');
        $builder->join('user_photos as org_photos', 'org_photos.user_id = organizations.user_id', 'left');
        
        $builder->where('forum_posts.id', $postId);
        
        $post = $builder->get()->getRowArray();
        
        if ($post) {
            // Format post to include author information
            if ($post['author_type'] === 'student') {
                $post['author_name'] = trim(($post['firstname'] ?? '') . ' ' . ($post['lastname'] ?? ''));
                $post['author_photo'] = !empty($post['photo_path']) ? base_url($post['photo_path']) : null;
                $post['author_badge'] = 'student';
            } else {
                $post['author_name'] = $post['org_name'] ?? 'Organization';
                $post['author_photo'] = !empty($post['org_photo']) ? base_url($post['org_photo']) : null;
                $post['author_badge'] = 'organization';
                $post['firstname'] = $post['org_name'] ?? '';
                $post['lastname'] = '';
            }
        }
        
        return $post;
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
        $builder->where('author_type', 'student');
        $builder->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get posts by organization ID
     */
    public function getPostsByOrganization($organizationId, $limit = null)
    {
        $builder = $this->db->table($this->table);
        $builder->where('organization_id', $organizationId);
        $builder->where('author_type', 'organization');
        $builder->orderBy('created_at', 'DESC');
        
        if ($limit) {
            $builder->limit($limit);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get post counts by category
     */
    public function getCategoryCounts()
    {
        $builder = $this->db->table($this->table);
        $builder->select('category, COUNT(*) as count');
        $builder->groupBy('category');
        $results = $builder->get()->getResultArray();
        
        $counts = [
            'all' => 0,
            'general' => 0,
            'events' => 0,
            'academics' => 0,
            'marketplace' => 0,
            'help' => 0
        ];
        
        foreach ($results as $result) {
            $category = $result['category'];
            $count = (int)$result['count'];
            if (isset($counts[$category])) {
                $counts[$category] = $count;
                $counts['all'] += $count;
            }
        }
        
        return $counts;
    }
}

