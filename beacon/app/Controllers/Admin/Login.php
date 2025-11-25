<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\StudentModel;

class Login extends BaseController
{
    protected $helpers = ['url', 'form', 'session'];
    protected $adminModel;
    protected $userModel;
    protected $userProfileModel;
    protected $studentModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->studentModel = new StudentModel();
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

        // Fetch active students with their profiles and student info
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        $builder->select('u.id as user_id, u.email, u.is_active, u.created_at, 
                         up.firstname, up.middlename, up.lastname,
                         s.id as student_id, s.course, s.department, s.in_organization, s.organization_name');
        $builder->join('user_profiles up', 'u.id = up.user_id', 'inner');
        $builder->join('students s', 'u.id = s.user_id', 'inner');
        $builder->where('u.role', 'student');
        $builder->where('u.is_active', 1);
        $builder->orderBy('u.created_at', 'DESC');
        $students = $builder->get()->getResultArray();

        // Format student data for display
        $studentData = [];
        foreach ($students as $student) {
            $fullName = trim(($student['firstname'] ?? '') . ' ' . ($student['middlename'] ?? '') . ' ' . ($student['lastname'] ?? ''));
            $fullName = preg_replace('/\s+/', ' ', $fullName); // Remove extra spaces
            
            // Count organizations (if in_organization is yes, count as 1)
            $orgCount = ($student['in_organization'] === 'yes' && !empty($student['organization_name'])) ? 1 : 0;
            
            // Get course name from departmentCourses mapping
            $courseName = $this->getCourseName($student['course'] ?? '');
            
            $studentData[] = [
                'id' => $student['user_id'],
                'name' => $fullName ?: 'N/A',
                'course' => $courseName ?: ($student['course'] ?? 'N/A'),
                'status' => $student['is_active'] ? 'Active' : 'Inactive',
                'org_count' => $orgCount,
                'email' => $student['email'] ?? 'N/A'
            ];
        }

        // Fetch all users for User Management section
        $usersBuilder = $db->table('users u');
        $usersBuilder->select('u.id as user_id, u.email, u.role, u.is_active, u.created_at,
                              up.firstname, up.middlename, up.lastname');
        $usersBuilder->join('user_profiles up', 'u.id = up.user_id', 'left');
        $usersBuilder->orderBy('u.created_at', 'DESC');
        $allUsers = $usersBuilder->get()->getResultArray();

        // Format user data for display
        $userData = [];
        foreach ($allUsers as $user) {
            $fullName = trim(($user['firstname'] ?? '') . ' ' . ($user['middlename'] ?? '') . ' ' . ($user['lastname'] ?? ''));
            $fullName = preg_replace('/\s+/', ' ', $fullName);
            
            $userData[] = [
                'id' => $user['user_id'],
                'name' => $fullName ?: 'N/A',
                'email' => $user['email'] ?? 'N/A',
                'role' => ucfirst($user['role'] ?? 'N/A'),
                'status' => $user['is_active'] ? 'Active' : 'Inactive',
                'registration_date' => $user['created_at'] ? date('Y-m-d', strtotime($user['created_at'])) : 'N/A'
            ];
        }

        // Calculate stats
        $activeStudentsCount = count($studentData);
        $totalStudentsCount = $db->table('users')->where('role', 'student')->countAllResults();
        
        // Count approved organizations (assuming organizations table exists, or use users with role='organization' and is_active=1)
        $approvedOrgsCount = $db->table('users')->where('role', 'organization')->where('is_active', 1)->countAllResults();
        
        // Count pending organizations (assuming organizations with is_active=0 or pending status)
        $pendingOrgsCount = $db->table('users')->where('role', 'organization')->where('is_active', 0)->countAllResults();

        $data = [
            'students' => $studentData,
            'users' => $userData,
            'stats' => [
                'active_students' => $activeStudentsCount,
                'approved_organizations' => $approvedOrgsCount,
                'pending_organizations' => $pendingOrgsCount
            ]
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Get course full name from course code
     */
    private function getCourseName($courseCode)
    {
        $courses = [
            'bsit' => 'Bachelor of Science in Information Technology',
            'bscs' => 'Bachelor of Science in Computer Science',
            'bsis' => 'Bachelor of Science in Information Systems',
            'blis' => 'Bachelor of Library Information Science',
            'bsce' => 'Bachelor of Science in Civil Engineering',
            'bsme' => 'Bachelor of Science in Mechanical Engineering',
            'bsece' => 'Bachelor of Science in Electronics Engineering',
            'bsee' => 'Bachelor of Science in Electrical Engineering',
            'bsoa' => 'Bachelor of Science in Office Administration',
            'bstm' => 'Bachelor of Science in Tourism Management',
            'bsem' => 'Bachelor of Science in Entrepreneurial Management',
            'bshm' => 'Bachelor of Science in Hospitality Management',
            'bsn' => 'Bachelor of Science in Nursing',
            'bsm' => 'Bachelor of Science in Midwifery',
            'bped' => 'Bachelor of Physical Education',
            'bcaed' => 'Bachelor of Culture and Arts Education',
            'bsne' => 'Bachelor of Special Needs Education',
            'btvted' => 'Bachelor of Technological Vocational Teacher Education',
            'baels' => 'Bachelor of Arts in English Language Studies',
            'bsmath' => 'Bachelor of Science in Mathematics',
            'bsam' => 'Bachelor of Science in Applied Mathematics',
            'bsdc' => 'Bachelor of Science in Development Communication',
            'bspa' => 'Bachelor of Science in Public Administration',
            'bshs' => 'Bachelor in Human Services',
            'dpbm' => 'Doctor of Philosophy in Business Management',
            'man' => 'Master of Arts in Nursing',
            'mbm' => 'Master in Business Management',
            'moe' => 'Master of Engineering'
        ];
        
        return $courses[$courseCode] ?? $courseCode;
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
