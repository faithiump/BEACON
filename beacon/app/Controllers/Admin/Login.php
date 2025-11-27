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

            return redirect()->to('/admin/dashboard')->with('success', 'Login successful! Welcome back.');
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
        // No limit here - we'll limit in the view
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

        // Limit initial display to 10 students
        $totalStudentsCount = count($studentData);

        // Fetch all users for User Management section (students and organizations)
        $usersBuilder = $db->table('users u');
        $usersBuilder->select('u.id as user_id, u.email, u.role, u.is_active, u.created_at,
                              up.firstname, up.middlename, up.lastname,
                              o.id as organization_id, o.organization_name');
        $usersBuilder->join('user_profiles up', 'u.id = up.user_id', 'left');
        $usersBuilder->join('organizations o', 'u.id = o.user_id', 'left');
        $usersBuilder->orderBy('u.created_at', 'DESC');
        $allUsers = $usersBuilder->get()->getResultArray();

        // Format user data for display
        $userData = [];
        foreach ($allUsers as $user) {
            // For organizations, use organization_name as Name
            if ($user['role'] === 'organization' && !empty($user['organization_name'])) {
                $displayName = $user['organization_name'];
            } else {
                // For students, use full name
                $fullName = trim(($user['firstname'] ?? '') . ' ' . ($user['middlename'] ?? '') . ' ' . ($user['lastname'] ?? ''));
                $displayName = preg_replace('/\s+/', ' ', $fullName) ?: 'N/A';
            }
            
            $userData[] = [
                'id' => $user['user_id'],
                'organization_id' => $user['organization_id'] ?? null,
                'name' => $displayName,
                'email' => $user['email'] ?? 'N/A',
                'role' => ucfirst($user['role'] ?? 'N/A'),
                'status' => $user['is_active'] ? 'Active' : 'Inactive',
                'registration_date' => $user['created_at'] ? date('Y-m-d', strtotime($user['created_at'])) : 'N/A'
            ];
        }

        // Fetch pending organization applications
        $pendingApplications = $db->table('organization_applications')
            ->where('status', 'pending')
            ->orderBy('submitted_at', 'DESC')
            ->get()
            ->getResultArray();

        // Format pending applications for display
        $pendingOrgsData = [];
        foreach ($pendingApplications as $app) {
            $submittedDate = new \DateTime($app['submitted_at']);
            $now = new \DateTime();
            $interval = $now->diff($submittedDate);
            
            $timeAgo = '';
            if ($interval->days > 0) {
                $timeAgo = $interval->days . ' day' . ($interval->days > 1 ? 's' : '') . ' ago';
            } elseif ($interval->h > 0) {
                $timeAgo = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } else {
                $timeAgo = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            }

            $pendingOrgsData[] = [
                'id' => $app['id'],
                'name' => $app['organization_name'],
                'type' => ucfirst(str_replace('_', ' ', $app['organization_type'])),
                'email' => $app['contact_email'],
                'phone' => $app['contact_phone'],
                'submitted_at' => $timeAgo
            ];
        }

        // Fetch approved organizations
        $approvedOrgs = $db->table('organizations o')
            ->select('o.id, o.organization_name, o.organization_category, o.is_active, o.created_at')
            ->where('o.is_active', 1)
            ->orderBy('o.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get approved dates from organization_applications
        $approvedDates = [];
        if (!empty($approvedOrgs)) {
            $orgNames = array_column($approvedOrgs, 'organization_name');
            $approvedApps = $db->table('organization_applications')
                ->select('organization_name, reviewed_at')
                ->whereIn('organization_name', $orgNames)
                ->where('status', 'approved')
                ->get()
                ->getResultArray();
            
            foreach ($approvedApps as $app) {
                $approvedDates[$app['organization_name']] = $app['reviewed_at'];
            }
        }

        // Format approved organizations for display
        $approvedOrgsData = [];
        foreach ($approvedOrgs as $org) {
            $approvedDate = $approvedDates[$org['organization_name']] ?? $org['created_at'];
            
            $approvedOrgsData[] = [
                'id' => $org['id'],
                'name' => $org['organization_name'],
                'category' => ucfirst(str_replace('_', ' ', $org['organization_category'])),
                'status' => $org['is_active'] ? 'Approved' : 'Inactive',
                'approved_date' => $approvedDate ? date('Y-m-d', strtotime($approvedDate)) : 'N/A'
            ];
        }

        // Fetch recent student comments
        try {
            $commentsBuilder = $db->table('post_comments');
            $commentsBuilder->select('post_comments.id, post_comments.content, post_comments.post_type, post_comments.post_id, post_comments.created_at,
                                      s.id as student_id, s.user_id,
                                      up.firstname, up.middlename, up.lastname,
                                      up_photo.photo_path');
            $commentsBuilder->join('students s', 's.id = post_comments.student_id', 'inner');
            $commentsBuilder->join('user_profiles up', 'up.user_id = s.user_id', 'inner');
            $commentsBuilder->join('user_photos up_photo', 'up_photo.user_id = s.user_id', 'left');
            // Only get top-level comments (not replies) - check if column exists first
            // Check if parent_comment_id column exists
            $columns = $db->getFieldNames('post_comments');
            if (in_array('parent_comment_id', $columns)) {
                $commentsBuilder->where('post_comments.parent_comment_id IS NULL');
            }
            // Only get student comments (not organization comments)
            $commentsBuilder->where('post_comments.student_id IS NOT NULL');
            $commentsBuilder->orderBy('post_comments.created_at', 'DESC');
            $commentsBuilder->limit(10);
            $recentComments = $commentsBuilder->get()->getResultArray();
        } catch (\Exception $e) {
            // If query fails, set empty array
            log_message('error', 'Failed to fetch recent comments: ' . $e->getMessage());
            $recentComments = [];
        }

        // Format comments for display
        $commentsData = [];
        foreach ($recentComments as $comment) {
            // Get full name
            $fullName = trim(($comment['firstname'] ?? '') . ' ' . ($comment['middlename'] ?? '') . ' ' . ($comment['lastname'] ?? ''));
            $fullName = preg_replace('/\s+/', ' ', $fullName) ?: 'N/A';
            
            // Get post title and organization name based on post_type
            $postTitle = '';
            $orgName = 'N/A';
            
            if ($comment['post_type'] === 'event') {
                $event = $db->table('events e')
                    ->select('e.event_name, o.organization_name')
                    ->join('organizations o', 'o.id = e.org_id', 'left')
                    ->where('e.event_id', $comment['post_id'])
                    ->get()
                    ->getRowArray();
                
                if ($event) {
                    $postTitle = $event['event_name'] ?? 'Event';
                    $orgName = $event['organization_name'] ?? 'N/A';
                }
            } elseif ($comment['post_type'] === 'announcement') {
                $announcement = $db->table('announcements a')
                    ->select('a.title, o.organization_name')
                    ->join('organizations o', 'o.id = a.org_id', 'left')
                    ->where('a.announcement_id', $comment['post_id'])
                    ->get()
                    ->getRowArray();
                
                if ($announcement) {
                    $postTitle = $announcement['title'] ?? 'Announcement';
                    $orgName = $announcement['organization_name'] ?? 'N/A';
                }
            }
            
            // Get profile image
            $profileImage = '';
            if (!empty($comment['photo_path']) && file_exists(FCPATH . $comment['photo_path'])) {
                $profileImage = base_url($comment['photo_path']);
            }
            
            // Calculate time ago
            $commentDate = new \DateTime($comment['created_at']);
            $now = new \DateTime();
            $interval = $now->diff($commentDate);
            
            $timeAgo = '';
            if ($interval->days > 0) {
                $timeAgo = $interval->days . ' day' . ($interval->days > 1 ? 's' : '') . ' ago';
            } elseif ($interval->h > 0) {
                $timeAgo = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
            } elseif ($interval->i > 0) {
                $timeAgo = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
            } else {
                $timeAgo = 'Just now';
            }
            
            $commentsData[] = [
                'id' => $comment['id'],
                'student_name' => $fullName,
                'content' => $comment['content'],
                'post_type' => ucfirst($comment['post_type']),
                'post_title' => $postTitle,
                'organization_name' => $orgName,
                'profile_image' => $profileImage,
                'time_ago' => $timeAgo,
                'created_at' => $comment['created_at']
            ];
        }

        // Fetch student activity data with memberships and comment counts
        $activityBuilder = $db->table('students s');
        $activityBuilder->select('s.id as student_id, s.user_id, s.course,
                                  up.firstname, up.middlename, up.lastname');
        $activityBuilder->join('user_profiles up', 'up.user_id = s.user_id', 'inner');
        $activityBuilder->join('users u', 'u.id = s.user_id', 'inner');
        $activityBuilder->where('u.role', 'student');
        $activityBuilder->where('u.is_active', 1);
        $activityBuilder->orderBy('s.id', 'DESC');
        $activityBuilder->limit(10); // Get top 10 most recent active students
        $activityStudents = $activityBuilder->get()->getResultArray();

        // Format activity data with memberships and metrics
        $activityData = [];
        foreach ($activityStudents as $student) {
            // Get full name
            $fullName = trim(($student['firstname'] ?? '') . ' ' . ($student['middlename'] ?? '') . ' ' . ($student['lastname'] ?? ''));
            $fullName = preg_replace('/\s+/', ' ', $fullName) ?: 'N/A';
            
            // Get course name
            $courseName = $this->getCourseName($student['course'] ?? '');
            
            // Get organization memberships
            $memberships = $db->table('student_organization_memberships som')
                ->select('o.organization_name')
                ->join('organizations o', 'o.id = som.org_id', 'inner')
                ->where('som.student_id', $student['student_id'])
                ->where('som.status', 'active')
                ->where('o.is_active', 1)
                ->get()
                ->getResultArray();
            
            $orgNames = array_column($memberships, 'organization_name');
            
            // Count comments
            $commentCount = $db->table('post_comments')
                ->where('student_id', $student['student_id'])
                ->countAllResults();
            
            // Count event likes (as proxy for events attended/interested)
            $eventLikesCount = $db->table('post_likes')
                ->where('student_id', $student['student_id'])
                ->where('post_type', 'event')
                ->countAllResults();
            
            $activityData[] = [
                'student_name' => $fullName,
                'course' => $courseName ?: ($student['course'] ?? 'N/A'),
                'organizations' => $orgNames,
                'comment_count' => $commentCount,
                'event_likes_count' => $eventLikesCount
            ];
        }

        // Calculate stats
        $activeStudentsCount = count($studentData);
        $totalStudentsCount = $db->table('users')->where('role', 'student')->countAllResults();
        
        // Count approved organizations from organization_applications
        $approvedOrgsCount = $db->table('organization_applications')->where('status', 'approved')->countAllResults();
        
        // Count pending organizations
        $pendingOrgsCount = count($pendingOrgsData);

        $data = [
            'students' => $studentData,
            'total_students_count' => $totalStudentsCount,
            'users' => $userData,
            'pending_organizations' => $pendingOrgsData,
            'approved_organizations' => $approvedOrgsData,
            'recent_comments' => $commentsData,
            'student_activity' => $activityData,
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

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get application details
            $application = $db->table('organization_applications')
                ->where('id', $id)
                ->where('status', 'pending')
                ->get()
                ->getRowArray();

            if (!$application) {
                return $this->response->setJSON(['success' => false, 'message' => 'Application not found or already processed']);
            }

            // Update application status
            $db->table('organization_applications')
                ->where('id', $id)
                ->update([
                    'status'      => 'approved',
                    'reviewed_by' => session()->get('admin_id'),
                    'reviewed_at' => date('Y-m-d H:i:s')
                ]);

            // Ensure unique organization user (re-use existing email if already registered)
            $userBuilder  = $db->table('users');
            $existingUser = $userBuilder->where('email', $application['contact_email'])->get()->getRowArray();
            $userId       = null;

            if ($existingUser) {
                $userId = $existingUser['id'];

                $userBuilder = $db->table('users');
                $userBuilder->where('id', $userId)->update([
                    'role'         => 'organization',
                    'is_active'    => 1,
                    'email_verified' => $existingUser['email_verified'] ?? 0
                ]);

                $error = $db->error();
                if (!empty($error['code'])) {
                    throw new \Exception('Failed to update existing user: ' . ($error['message'] ?? 'Unknown error'));
                }
            } else {
                // Create user account for organization using password from application
                $userData = [
                    'email'          => $application['contact_email'],
                    'password'       => $application['password_hash'], // Use password set during registration
                    'role'           => 'organization',
                    'is_active'      => 1,
                    'email_verified' => 0
                ];
                $userBuilder = $db->table('users');
                $userBuilder->insert($userData);
                $userId = $db->insertID();

                $error = $db->error();
                if (!$userId || !empty($error['code'])) {
                    throw new \Exception('Failed to create organization user: ' . ($error['message'] ?? 'Unknown error'));
                }
            }

            // Create organization record
            $orgData = [
                'user_id' => $userId,
                'organization_name' => $application['organization_name'],
                'organization_acronym' => $application['organization_acronym'],
                'organization_type' => $application['organization_type'],
                'organization_category' => $application['organization_category'],
                'founding_date' => $application['founding_date'],
                'mission' => $application['mission'],
                'vision' => $application['vision'],
                'objectives' => $application['objectives'],
                'contact_email' => $application['contact_email'],
                'contact_phone' => $application['contact_phone'],
                'current_members' => $application['current_members'],
                'is_active' => 1
            ];
            $orgBuilder = $db->table('organizations');
            $existingOrg = $orgBuilder->where('organization_name', $application['organization_name'])->get()->getRowArray();

            if ($existingOrg) {
                throw new \Exception('Organization name already exists in the system.');
            }

            $orgBuilder->insert($orgData);
            $error = $db->error();
            if (!empty($error['code'])) {
                throw new \Exception('Failed to create organization record: ' . ($error['message'] ?? 'Unknown error'));
            }

            // Get advisor and officer info for email
            $advisor = $db->table('organization_advisors')
                ->where('application_id', $id)
                ->get()
                ->getRowArray();

            $officer = $db->table('organization_officers')
                ->where('application_id', $id)
                ->get()
                ->getRowArray();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

            // Send approval email notification
            $this->sendApprovalEmail($application, $advisor, $officer);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Organization approved successfully. Email notification sent.'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Organization approval error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error approving organization: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Send approval email notification
     */
    private function sendApprovalEmail($application, $advisor, $officer)
    {
        $email = \Config\Services::email();

        $subject = 'Organization Application Approved - ' . $application['organization_name'];
        $message = view('emails/organization_approved', [
            'application' => $application,
            'advisor'     => $advisor,
            'officer'     => $officer,
        ]);

        $email->setTo($application['contact_email']);

        $ccRecipients = [];
        if (!empty($advisor['email'])) {
            $ccRecipients[] = $advisor['email'];
        }
        if (!empty($officer['email'])) {
            $ccRecipients[] = $officer['email'];
        }
        if (!empty($ccRecipients)) {
            $email->setCC($ccRecipients);
        }

        $email->setSubject($subject);
        $email->setMessage($message);

        try {
            $email->send();
            log_message('info', 'Approval email sent to: ' . $application['contact_email']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send approval email: ' . $e->getMessage());
        }
    }

    /**
     * Reject organization account
     */
    public function rejectOrganization($id)
    {
        if (!session()->get('is_admin')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $db = \Config\Database::connect();

        try {
            // Get application details
            $application = $db->table('organization_applications')
                ->where('id', $id)
                ->where('status', 'pending')
                ->get()
                ->getRowArray();

            if (!$application) {
                return $this->response->setJSON(['success' => false, 'message' => 'Application not found or already processed']);
            }

            // Update application status
            $db->table('organization_applications')
                ->where('id', $id)
                ->update([
                    'status' => 'rejected',
                    'reviewed_by' => session()->get('admin_id'),
                    'reviewed_at' => date('Y-m-d H:i:s')
                ]);

            // Get advisor and officer info for email
            $advisor = $db->table('organization_advisors')
                ->where('application_id', $id)
                ->get()
                ->getRowArray();

            $officer = $db->table('organization_officers')
                ->where('application_id', $id)
                ->get()
                ->getRowArray();

            // Send rejection email notification
            $this->sendRejectionEmail($application, $advisor, $officer);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Organization rejected. Email notification sent.'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Organization rejection error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error rejecting organization: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Send rejection email notification
     */
    private function sendRejectionEmail($application, $advisor, $officer)
    {
        $email = \Config\Services::email();

        $subject = 'Organization Application Status - ' . $application['organization_name'];
        $message = view('emails/organization_rejected', [
            'application' => $application,
            'advisor'     => $advisor,
            'officer'     => $officer,
        ]);

        $email->setTo($application['contact_email']);

        $ccRecipients = [];
        if (!empty($advisor['email'])) {
            $ccRecipients[] = $advisor['email'];
        }
        if (!empty($officer['email'])) {
            $ccRecipients[] = $officer['email'];
        }
        if (!empty($ccRecipients)) {
            $email->setCC($ccRecipients);
        }

        $email->setSubject($subject);
        $email->setMessage($message);

        try {
            $email->send();
            log_message('info', 'Rejection email sent to: ' . $application['contact_email']);
        } catch (\Exception $e) {
            log_message('error', 'Failed to send rejection email: ' . $e->getMessage());
        }
    }

    /**
     * View organization details
     */
    public function viewOrganization($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $db = \Config\Database::connect();

        // First, check if this is an application ID (for pending organizations)
        $application = $db->table('organization_applications')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        $organization = null;
        $isPending = false;

        if ($application) {
            // This is a pending application
            $isPending = true;
            
            // Check if organization exists in organizations table
            $organization = $db->table('organizations')
                ->where('organization_name', $application['organization_name'])
                ->get()
                ->getRowArray();
            
            // If organization doesn't exist, create a mock organization object from application data
            if (!$organization) {
                $organization = [
                    'id' => null,
                    'organization_name' => $application['organization_name'],
                    'organization_acronym' => $application['organization_acronym'] ?? '',
                    'organization_type' => $application['organization_type'],
                    'organization_category' => $application['organization_category'] ?? '',
                    'mission' => $application['mission'] ?? '',
                    'vision' => $application['vision'] ?? '',
                    'objectives' => $application['objectives'] ?? '',
                    'founding_date' => $application['founding_date'] ?? null,
                    'contact_email' => $application['contact_email'],
                    'contact_phone' => $application['contact_phone'],
                    'is_active' => 0,
                    'user_id' => null,
                    'current_members' => $application['current_members'] ?? 0,
                    'created_at' => $application['submitted_at']
                ];
            }
        } else {
            // This is an organization ID, fetch organization details
            $organization = $db->table('organizations')
                ->where('id', $id)
                ->get()
                ->getRowArray();

            if (!$organization) {
                return redirect()->to('/admin/dashboard')->with('error', 'Organization not found');
            }

            // Find related application
            $application = $db->table('organization_applications')
                ->where('organization_name', $organization['organization_name'])
                ->where('status', 'approved')
                ->orderBy('reviewed_at', 'DESC')
                ->get()
                ->getRowArray();
        }

        // Get advisor information (from application)
        $advisor = null;
        if ($application) {
            $advisor = $db->table('organization_advisors')
                ->where('application_id', $application['id'])
                ->get()
                ->getRowArray();
        }

        // Get officers information (from application)
        $officers = [];
        if ($application) {
            $officers = $db->table('organization_officers')
                ->where('application_id', $application['id'])
                ->get()
                ->getResultArray();
        }

        // Get files (from application)
        $files = [];
        if ($application) {
            $files = $db->table('organization_files')
                ->where('application_id', $application['id'])
                ->get()
                ->getResultArray();
        }

        // Get organization statistics (only if organization exists)
        $eventsCount = 0;
        $announcementsCount = 0;
        $productsCount = 0;
        $membersCount = 0;
        
        if ($organization && $organization['id']) {
            $eventsCount = $db->table('events')
                ->where('org_id', $organization['id'])
                ->countAllResults();

            $announcementsCount = $db->table('announcements')
                ->where('org_id', $organization['id'])
                ->countAllResults();

            $productsCount = $db->table('products')
                ->where('org_id', $organization['id'])
                ->countAllResults();

            $membersCount = $db->table('student_organization_memberships')
                ->where('org_id', $organization['id'])
                ->where('status', 'active')
                ->countAllResults();
        }

        // Get user account info
        $user = null;
        if ($organization && isset($organization['user_id']) && $organization['user_id']) {
            $user = $db->table('users')
                ->where('id', $organization['user_id'])
                ->get()
                ->getRowArray();
        }

        // Get return parameter
        $returnTo = $this->request->getGet('return') ?? 'organizations';
        
        // Format data for view
        $data = [
            'organization' => $organization,
            'application' => $application,
            'advisor' => $advisor,
            'officers' => $officers,
            'files' => $files,
            'user' => $user,
            'isPending' => $isPending,
            'stats' => [
                'events' => $eventsCount,
                'announcements' => $announcementsCount,
                'products' => $productsCount,
                'members' => $membersCount
            ],
            'returnTo' => $returnTo
        ];

        return view('admin/organization_details', $data);
    }

    /**
     * View organization file
     */
    public function viewOrganizationFile($fileId)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $db = \Config\Database::connect();

        // Fetch file details
        $file = $db->table('organization_files')
            ->where('id', $fileId)
            ->get()
            ->getRowArray();

        if (!$file) {
            return redirect()->to('/admin/dashboard')->with('error', 'File not found');
        }

        // Check if file exists - files are stored in WRITEPATH but path in DB is relative to public
        $filePath = null;
        if (strpos($file['file_path'], 'uploads/') === 0) {
            // File path in DB is "uploads/organizations/..." but actual file is in WRITEPATH
            // Try WRITEPATH first (where files are actually stored)
            $filePath = WRITEPATH . $file['file_path'];
            if (!file_exists($filePath)) {
                // Try public path as fallback
                $filePath = FCPATH . $file['file_path'];
            }
        } else {
            // If path doesn't start with uploads/, assume it's already a full path
            $filePath = WRITEPATH . $file['file_path'];
            if (!file_exists($filePath)) {
                $filePath = FCPATH . $file['file_path'];
            }
        }

        if (!file_exists($filePath)) {
            return redirect()->to('/admin/dashboard')->with('error', 'File not found on server: ' . $file['file_path']);
        }

        // Get file extension
        $extension = strtolower(pathinfo($file['file_name'], PATHINFO_EXTENSION));
        
        // For PDF files, serve directly with inline display
        if ($extension === 'pdf') {
            $this->response->setHeader('Content-Type', 'application/pdf');
            $this->response->setHeader('Content-Disposition', 'inline; filename="' . $file['file_name'] . '"');
            $this->response->setHeader('Content-Length', filesize($filePath));
            $this->response->setHeader('Cache-Control', 'private, max-age=3600');
            return $this->response->sendFile($filePath);
        }

        // For DOC/DOCX files, try to serve directly first
        // If browser can't display, user can download
        $mimeType = $file['mime_type'] ?? mime_content_type($filePath);
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $file['file_name'] . '"');
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Cache-Control', 'private, max-age=3600');
        
        // For DOC/DOCX, return view with iframe that tries to display the file
        // If browser can't display, it will offer download
        $data = [
            'file' => $file,
            'fileUrl' => base_url('admin/organizations/file/' . $fileId . '/download'),
            'extension' => $extension
        ];
        
        return view('admin/view_file', $data);
    }

    /**
     * Download organization file (used for Google Docs Viewer)
     */
    public function downloadOrganizationFile($fileId)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $db = \Config\Database::connect();

        // Fetch file details
        $file = $db->table('organization_files')
            ->where('id', $fileId)
            ->get()
            ->getRowArray();

        if (!$file) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        // Check if file exists
        $filePath = null;
        if (strpos($file['file_path'], 'uploads/') === 0) {
            $filePath = WRITEPATH . $file['file_path'];
            if (!file_exists($filePath)) {
                $filePath = FCPATH . $file['file_path'];
            }
        } else {
            $filePath = WRITEPATH . $file['file_path'];
            if (!file_exists($filePath)) {
                $filePath = FCPATH . $file['file_path'];
            }
        }

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found on server');
        }

        // Serve the file
        $mimeType = $file['mime_type'] ?? mime_content_type($filePath);
        $this->response->setHeader('Content-Type', $mimeType);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $file['file_name'] . '"');
        $this->response->setHeader('Content-Length', filesize($filePath));
        $this->response->setHeader('Cache-Control', 'private, max-age=3600');
        
        return $this->response->sendFile($filePath);
    }

    /**
     * View student details
     */
    public function viewStudent($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $db = \Config\Database::connect();

        // Fetch user account info
        $user = $db->table('users')
            ->where('id', $id)
            ->where('role', 'student')
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->to('/admin/dashboard')->with('error', 'Student not found');
        }

        // Fetch user profile
        $profile = $db->table('user_profiles')
            ->where('user_id', $id)
            ->get()
            ->getRowArray();

        // Fetch student info
        $student = $db->table('students')
            ->where('user_id', $id)
            ->get()
            ->getRowArray();

        if (!$student) {
            return redirect()->to('/admin/dashboard')->with('error', 'Student information not found');
        }

        // Get full name
        $fullName = trim(($profile['firstname'] ?? '') . ' ' . ($profile['middlename'] ?? '') . ' ' . ($profile['lastname'] ?? ''));
        $fullName = preg_replace('/\s+/', ' ', $fullName);

        // Get course name
        $courseName = $this->getCourseName($student['course'] ?? '');

        // Get organization memberships
        $memberships = $db->table('student_organization_memberships som')
            ->select('som.*, o.organization_name, o.organization_acronym, o.organization_type, o.organization_category')
            ->join('organizations o', 'o.id = som.org_id', 'inner')
            ->where('som.student_id', $student['id'])
            ->orderBy('som.joined_at', 'DESC')
            ->get()
            ->getResultArray();

        // Get address if available
        $address = null;
        if ($profile && $profile['address_id']) {
            $address = $db->table('addresses')
                ->where('id', $profile['address_id'])
                ->get()
                ->getRowArray();
        }

        // Format address string
        $addressString = '';
        if ($address) {
            $parts = array_filter([
                $address['street'] ?? '',
                $address['barangay'] ?? '',
                $address['city'] ?? '',
                $address['province'] ?? '',
                $address['postal_code'] ?? ''
            ]);
            $addressString = implode(', ', $parts);
        }

        // Get statistics
        $activeMembershipsCount = $db->table('student_organization_memberships')
            ->where('student_id', $student['id'])
            ->where('status', 'active')
            ->countAllResults();

        // Get return parameter
        $returnTo = $this->request->getGet('return') ?? 'students';
        
        // Format data for view
        $data = [
            'student' => [
                'id' => $user['id'],
                'student_id' => $student['student_id'],
                'name' => $fullName ?: 'N/A',
                'firstname' => $profile['firstname'] ?? '',
                'middlename' => $profile['middlename'] ?? '',
                'lastname' => $profile['lastname'] ?? '',
                'email' => $user['email'],
                'phone' => $profile['phone'] ?? 'N/A',
                'birthday' => $profile['birthday'] ?? null,
                'gender' => $profile['gender'] ?? 'N/A',
                'address' => $addressString,
                'course' => $courseName ?: ($student['course'] ?? 'N/A'),
                'department' => $student['department'] ?? 'N/A',
                'year_level' => $student['year_level'] ?? 'N/A',
                'is_active' => $user['is_active'],
                'email_verified' => $user['email_verified'],
                'created_at' => $user['created_at'],
                'updated_at' => $user['updated_at']
            ],
            'memberships' => $memberships,
            'stats' => [
                'organizations' => $activeMembershipsCount
            ],
            'returnTo' => $returnTo
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
