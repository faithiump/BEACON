<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[3]|max_length[255]'
    ];

    /**
     * Verify admin credentials
     * 
     * @param string $username
     * @param string $password
     * @return array|false Returns admin data if credentials are valid, false otherwise
     */
    public function verifyCredentials($username, $password)
    {
        $admin = $this->where('username', $username)->first();
        
        if ($admin && $admin['password'] === $password) {
            return $admin;
        }
        
        return false;
    }

    /**
     * Get admin by username
     * 
     * @param string $username
     * @return array|false
     */
    public function getAdminByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
}
