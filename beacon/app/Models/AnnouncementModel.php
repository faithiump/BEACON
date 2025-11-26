<?php

namespace App\Models;

use CodeIgniter\Model;

class AnnouncementModel extends Model
{
    protected $table = 'announcements';
    protected $primaryKey = 'announcement_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'org_id',
        'title',
        'content',
        'priority',
        'views',
        'is_pinned'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'org_id' => 'required|integer',
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'priority' => 'permit_empty|in_list[normal,high]',
        'views' => 'permit_empty|integer|greater_than_equal_to[0]',
        'is_pinned' => 'permit_empty|integer|in_list[0,1]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Announcement title is required',
            'min_length' => 'Title must be at least 3 characters',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'content' => [
            'required' => 'Announcement content is required',
            'min_length' => 'Content must be at least 10 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get announcements by organization ID
     */
    public function getAnnouncementsByOrg($orgId, $limit = null)
    {
        $builder = $this->where('org_id', $orgId)
            ->orderBy('is_pinned', 'DESC')
            ->orderBy('created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get pinned announcements
     */
    public function getPinnedAnnouncements($orgId = null, $limit = null)
    {
        $builder = $this->where('is_pinned', 1)
            ->orderBy('created_at', 'DESC');

        if ($orgId) {
            $builder->where('org_id', $orgId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Get high priority announcements
     */
    public function getHighPriorityAnnouncements($orgId = null, $limit = null)
    {
        $builder = $this->where('priority', 'high')
            ->orderBy('created_at', 'DESC');

        if ($orgId) {
            $builder->where('org_id', $orgId);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    /**
     * Increment view count
     */
    public function incrementViews($announcementId)
    {
        $announcement = $this->find($announcementId);
        if ($announcement) {
            $this->update($announcementId, [
                'views' => ($announcement['views'] ?? 0) + 1
            ]);
        }
    }
}

