<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentOrganizationMembershipModel extends Model
{
    protected $table            = 'student_organization_memberships';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id',
        'org_id',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'joined_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all organizations a student has joined (active only)
     */
    public function getStudentOrganizations($studentId)
    {
        return $this->select('student_organization_memberships.*, organizations.organization_name, organizations.organization_acronym')
            ->join('organizations', 'organizations.id = student_organization_memberships.org_id')
            ->where('student_organization_memberships.student_id', $studentId)
            ->where('student_organization_memberships.status', 'active')
            ->where('organizations.is_active', 1)
            ->findAll();
    }

    /**
     * Get all organizations a student has (including pending)
     */
    public function getStudentAllOrganizations($studentId)
    {
        return $this->select('student_organization_memberships.*, organizations.organization_name, organizations.organization_acronym')
            ->join('organizations', 'organizations.id = student_organization_memberships.org_id')
            ->where('student_organization_memberships.student_id', $studentId)
            ->where('organizations.is_active', 1)
            ->findAll();
    }

    /**
     * Check if student is a member of an organization (active only)
     */
    public function isMember($studentId, $orgId)
    {
        return $this->where('student_id', $studentId)
            ->where('org_id', $orgId)
            ->where('status', 'active')
            ->first();
    }

    /**
     * Check if student has any membership (pending or active) for an organization
     */
    public function hasMembership($studentId, $orgId)
    {
        return $this->where('student_id', $studentId)
            ->where('org_id', $orgId)
            ->first();
    }

    /**
     * Get all organization IDs a student has joined (active only)
     */
    public function getStudentOrgIds($studentId)
    {
        $memberships = $this->where('student_id', $studentId)
            ->where('status', 'active')
            ->findAll();
        
        return array_column($memberships, 'org_id');
    }

    /**
     * Get pending memberships for an organization
     */
    public function getPendingMemberships($orgId)
    {
        return [];
    }

    /**
     * Get active memberships for an organization
     */
    public function getActiveMemberships($orgId)
    {
        return $this->select('student_organization_memberships.*, students.student_id, students.user_id, students.course, students.year_level, user_profiles.firstname, user_profiles.lastname')
            ->join('students', 'students.id = student_organization_memberships.student_id')
            ->join('user_profiles', 'user_profiles.user_id = students.user_id')
            ->where('student_organization_memberships.org_id', $orgId)
            ->where('student_organization_memberships.status', 'active')
            ->findAll();
    }

    /**
     * Get all non-pending memberships (active or inactive)
     */
    public function getNonPendingMemberships($orgId)
    {
        return $this->select('student_organization_memberships.*, students.student_id, students.user_id, students.course, students.year_level, user_profiles.firstname, user_profiles.lastname')
            ->join('students', 'students.id = student_organization_memberships.student_id')
            ->join('user_profiles', 'user_profiles.user_id = students.user_id')
            ->where('student_organization_memberships.org_id', $orgId)
            ->where('student_organization_memberships.status !=', 'pending')
            ->findAll();
    }

    /**
     * Count all non-pending memberships
     */
    public function countNonPendingMemberships($orgId)
    {
        return $this->where('org_id', $orgId)
            ->where('status !=', 'pending')
            ->countAllResults();
    }
}

