<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationApplicationModel extends Model
{
    protected $table            = 'organization_applications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'organization_name',
        'organization_acronym',
        'organization_type',
        'organization_category',
        'founding_date',
        'mission',
        'vision',
        'objectives',
        'contact_email',
        'contact_phone',
        'current_members',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
        'submitted_at'
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
}

