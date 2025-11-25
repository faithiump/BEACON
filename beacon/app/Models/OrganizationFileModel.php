<?php

namespace App\Models;

use CodeIgniter\Model;

class OrganizationFileModel extends Model
{
    protected $table            = 'organization_files';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'application_id',
        'file_type',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_at'
    ];

    // Dates
    protected $useTimestamps = false; // Uses uploaded_at instead (handled by database default)
    protected $dateFormat    = 'datetime';
    protected $createdField  = null;
    protected $updatedField  = null;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}

