<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Login extends BaseController
{
    protected $helpers = ['url', 'form', 'session'];
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        return view('admin/login');
    }

    public function process()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Verify credentials against database
        $admin = $this->adminModel->verifyCredentials($username, $password);

        if ($admin) {
            session()->set([
                'is_admin' => true,
                'admin_user' => $username,
                'admin_id' => $admin['id']
            ]);

            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid admin credentials');
    }

    public function dashboard()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/dashboard');
    }

    public function logout()
    {
        session()->remove(['is_admin', 'admin_user', 'admin_id']);
        return redirect()->to('/admin/login')->with('success', 'You have been logged out');
    }

    /**
     * Approve organization account
     */
    public function approveOrganization($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // TODO: Implement organization approval logic
        // Update organization status to 'approved' in database
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Organization approved successfully'
        ]);
    }

    /**
     * Reject organization account
     */
    public function rejectOrganization($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // TODO: Implement organization rejection logic
        // Update organization status to 'rejected' in database
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Organization rejected'
        ]);
    }

    /**
     * View organization details
     */
    public function viewOrganization($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        // TODO: Fetch organization details from database
        $data = [
            'organization' => [
                'id' => $id,
                'name' => 'Sample Organization',
                'email' => 'org@example.com',
                'status' => 'pending'
            ]
        ];

        return view('admin/organization_details', $data);
    }

    /**
     * View student details
     */
    public function viewStudent($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        // TODO: Fetch student details, comments, activities, transactions from database
        $data = [
            'student' => [
                'id' => $id,
                'name' => 'Sample Student',
                'email' => 'student@example.com',
                'course' => 'Computer Science',
                'organizations' => [],
                'comments' => [],
                'transactions' => []
            ]
        ];

        return view('admin/student_details', $data);
    }

    /**
     * Manage users
     */
    public function manageUsers()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        // TODO: Fetch all users (students and organizations) from database
        
        return view('admin/users');
    }
}
