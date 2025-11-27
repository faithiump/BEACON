<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationFollowModel extends Model
{
    protected $table            = 'organization_follows';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'org_id'
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
     * Check if student is following an organization
     */
    public function isFollowing($studentId, $orgId)
    {
        return $this->where('student_id', $studentId)
                    ->where('org_id', $orgId)
                    ->first() !== null;
    }

    /**
     * Get all organizations a student is following
     */
    public function getFollowedOrganizations($studentId)
    {
        return $this->where('student_id', $studentId)
                    ->findAll();
    }

    /**
     * Get all students following an organization
     */
    public function getOrganizationFollowers($orgId)
    {
        return $this->where('org_id', $orgId)
                    ->findAll();
    }
}


