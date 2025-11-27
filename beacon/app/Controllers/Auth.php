<?php

namespace App\Controllers;
use Config\Google;
use GuzzleHttp\Client as GuzzleClient;

// Load Composer autoload if not already loaded
if (defined('COMPOSER_PATH')) {
    require_once COMPOSER_PATH;
} elseif (file_exists(ROOTPATH . 'vendor/autoload.php')) {
    require_once ROOTPATH . 'vendor/autoload.php';
}

use App\Models\UserModel;
use App\Models\UserProfileModel;
use App\Models\UserPhotoModel;
use App\Models\AddressModel;
use App\Models\StudentModel;

class Auth extends BaseController
{
    protected $helpers = ['url'];
    
    protected $userModel;
    protected $userProfileModel;
    protected $addressModel;
    protected $studentModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->userProfileModel = new UserProfileModel();
        $this->addressModel = new AddressModel();
        $this->studentModel = new StudentModel();
    }

    public function login(): string
    {
        return view('auth/login');
    }

    public function register(): string
    {
        return view('auth/register');
    }

    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'role' => 'required|in_list[student,organization]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            $firstError = array_shift($errors);
            return redirect()->back()->withInput()->with('error', $firstError ?? 'Invalid login data.');
        }

        $role = $this->request->getPost('role');
        $email = strtolower(trim($this->request->getPost('email')));
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || $user['role'] !== $role) {
            return redirect()->back()->withInput()->with('error', 'Invalid credentials for the selected role.');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Incorrect email or password.');
        }

        if (empty($user['is_active'])) {
            return redirect()->back()->withInput()->with('error', 'Your account is currently inactive.');
        }

        if ($role === 'student') {
            return $this->handleStudentLogin($user);
        }

        if ($role === 'organization') {
            $organizationModel = new \App\Models\OrganizationModel();
            $organization = $organizationModel->where('user_id', $user['id'])->first();

            if (!$organization) {
                return redirect()->back()->withInput()->with('error', 'Organization record not found.');
            }

            // Get photo from user_photos table
            $userPhotoModel = new UserPhotoModel();
            $userPhoto = $userPhotoModel->where('user_id', $user['id'])->first();
            $photoUrl = null;
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $photoUrl = base_url($userPhoto['photo_path']);
            }

            session()->set([
                'isLoggedIn'           => true,
                'role'                 => 'organization',
                'user_id'              => $user['id'],
                'organization_id'      => $organization['id'],
                'organization_name'    => $organization['organization_name'],
                'organization_acronym' => $organization['organization_acronym'],
                'email'                => $user['email'],
                'photo'                => $photoUrl
            ]);

            return redirect()->to(base_url('organization/dashboard'))->with('success', 'Welcome back!');
        }

        return redirect()->back()->withInput()->with('error', 'Invalid role selected.');
    }

    private function handleStudentLogin(array $user)
    {
        $student = $this->studentModel->where('user_id', $user['id'])->first();

        if (!$student) {
            return redirect()->back()->withInput()->with('error', 'Student record not found.');
        }

        $profile = $this->userProfileModel->where('user_id', $user['id'])->first();
        $fullName = null;
        if ($profile) {
            $fullName = trim(($profile['firstname'] ?? '') . ' ' . ($profile['lastname'] ?? ''));
        }

        // Get photo from user_photos table
        $userPhotoModel = new UserPhotoModel();
        $userPhoto = $userPhotoModel->where('user_id', $user['id'])->first();
        $photoUrl = null;
        if ($userPhoto && !empty($userPhoto['photo_path'])) {
            $photoUrl = base_url($userPhoto['photo_path']);
        }

        session()->set([
            'isLoggedIn'     => true,
            'role'           => 'student',
            'user_id'        => $user['id'],
            'student_id'     => $student['id'],
            'student_number' => $student['student_id'],
            'email'          => $user['email'],
            'name'           => $fullName ?: null,
            'photo'          => $photoUrl
        ]);

        return redirect()->to(base_url('student/dashboard'))->with('success', 'Welcome back!');
    }

    public function processRegister()
    {
        // Debug: Log that we reached this method
        log_message('info', 'processRegister method called');
        log_message('info', 'POST data: ' . json_encode($this->request->getPost()));
        
        // Handle registration logic here
        $validation = \Config\Services::validation();
        
        // Role is automatically set to 'student' for register page
        $role = 'student';
        
        $rules = [
            'firstname' => 'required|min_length[2]',
            'middlename' => 'required|min_length[2]',
            'lastname' => 'required|min_length[2]',
            'birthday' => 'required|valid_date',
            'gender' => 'required',
            'phone' => 'required',
            'province' => 'required|min_length[3]',
            'city_municipality' => 'required|min_length[3]',
            'barangay' => 'required|min_length[3]',
            'role' => 'required|in_list[student]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'student_id' => 'required|min_length[5]',
            'department' => 'required|in_list[ccs,cea,cthbm,chs,ctde,cas,gs]',
            'course' => 'required',
            'year_level' => 'required|in_list[1,2,3,4,5]',
        ];

        if (!$this->validate($rules)) {
            $errors = $validation->getErrors();
            log_message('error', 'Registration validation failed: ' . json_encode($errors));
            return redirect()->to(base_url('auth/register'))->withInput()->with('errors', $errors);
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Create address record
            $addressData = [
                'province' => $this->request->getPost('province'),
                'city_municipality' => $this->request->getPost('city_municipality'),
                'barangay' => $this->request->getPost('barangay')
            ];
            $addressId = $this->addressModel->insert($addressData);
            
            if (!$addressId) {
                throw new \Exception('Failed to create address record: ' . json_encode($this->addressModel->errors()));
            }

            // 2. Create user account
            $userData = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'), // Will be hashed by model
                'role' => 'student',
                'is_active' => 1,
                'email_verified' => 0
            ];
            $userId = $this->userModel->insert($userData);
            
            if (!$userId) {
                throw new \Exception('Failed to create user account: ' . json_encode($this->userModel->errors()));
            }

            // 3. Create user profile
            $profileData = [
                'user_id' => $userId,
                'firstname' => $this->request->getPost('firstname'),
                'middlename' => $this->request->getPost('middlename'),
                'lastname' => $this->request->getPost('lastname'),
                'birthday' => $this->request->getPost('birthday'),
                'gender' => $this->request->getPost('gender'),
                'phone' => $this->request->getPost('phone'),
                'address_id' => $addressId
            ];
            $profileId = $this->userProfileModel->insert($profileData);
            
            if (!$profileId) {
                throw new \Exception('Failed to create user profile: ' . json_encode($this->userProfileModel->errors()));
            }

            // 4. Create student record
            $studentData = [
                'user_id' => $userId,
                'student_id' => $this->request->getPost('student_id'),
                'department' => $this->request->getPost('department'),
                'course' => $this->request->getPost('course'),
                'year_level' => $this->request->getPost('year_level')
            ];
            $studentId = $this->studentModel->insert($studentData);
            
            if (!$studentId) {
                throw new \Exception('Failed to create student record: ' . json_encode($this->studentModel->errors()));
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', 'Database transaction failed during registration');
                return redirect()->to(base_url('auth/register'))->withInput()->with('errors', ['Database error occurred. Please try again.']);
            }

            // Auto-follow organizations in the same department
            $studentDepartment = $this->request->getPost('department');
            if ($studentDepartment) {
                try {
                    $followModel = new \App\Models\OrganizationFollowModel();
                    
                    // Get all approved organizations with the same department
                    $organizations = $db->table('organization_applications')
                        ->select('organization_applications.organization_name')
                        ->join('organizations', 'organizations.organization_name = organization_applications.organization_name', 'inner')
                        ->where('organization_applications.status', 'approved')
                        ->where('organization_applications.department', $studentDepartment)
                        ->where('organizations.is_active', 1)
                        ->get()
                        ->getResultArray();
                    
                    // Auto-follow each organization
                    foreach ($organizations as $org) {
                        $orgRecord = $db->table('organizations')
                            ->where('organization_name', $org['organization_name'])
                            ->where('is_active', 1)
                            ->get()
                            ->getRowArray();
                        
                        if ($orgRecord && !$followModel->isFollowing($studentId, $orgRecord['id'])) {
                            $followModel->insert([
                                'student_id' => $studentId,
                                'org_id' => $orgRecord['id']
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail registration
                    log_message('error', 'Auto-follow error during registration: ' . $e->getMessage());
                }
            }

            log_message('info', 'Student registration successful for email: ' . $this->request->getPost('email'));
            return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            if (isset($db)) {
                $db->transRollback();
            }
            log_message('error', 'Registration error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return redirect()->to(base_url('auth/register'))->withInput()->with('errors', ['An error occurred during registration: ' . $e->getMessage()]);
        }
    }
    
    public function googleLogin()
    {
        $config = new Google();
        $client = new \Google\Client();
        $client->setClientId($config->clientID);
        $client->setClientSecret($config->clientSecret);
        $client->setRedirectUri($config->redirectUri);
        $client->addScope('email');
        $client->addScope('profile');
        
        // Also set login_hint to empty to not pre-fill email
        $client->setLoginHint('');
        
        // Disable SSL verification locally
        $httpClient = new GuzzleClient(['verify' => false]);
        $client->setHttpClient($httpClient);
    
        return redirect()->to($client->createAuthUrl());
    }
    
    public function googleCallback()
    {
        $config = new Google();
        $client = new \Google\Client();
        $client->setClientId($config->clientID);
        $client->setClientSecret($config->clientSecret);
        $client->setRedirectUri($config->redirectUri);
    
        // Disable SSL verification locally
        $httpClient = new GuzzleClient(['verify' => false]);
        $client->setHttpClient($httpClient);
    
        if (! $this->request->getGet('code')) {
            return redirect()->to('/auth/login');
        }
    
        $token = $client->fetchAccessTokenWithAuthCode($this->request->getGet('code'));
        if (isset($token['error'])) {
            return redirect()->to('/auth/login')->with('error', 'Google login failed');
        }
    
        $client->setAccessToken($token['access_token']);
        $oauth2 = new \Google\Service\Oauth2($client);
        $googleUser = $oauth2->userinfo->get();
    
        // Load models
        $userModel         = new \App\Models\UserModel();
        $studentModel      = new \App\Models\StudentModel();
        $organizationModel = new \App\Models\OrganizationModel();
    
        /* ----------------------------------------------------------
         * 1. CHECK USERS TABLE BY EMAIL
         * ---------------------------------------------------------- */
        $user = $userModel->where('email', $googleUser->email)->first();
    
        if (! $user) {
            return redirect()->to('/auth/login')
                ->with('error', 'Your Google account is not registered in the system.');
        }
    
        // Check if user role is student or organization
        if ($user['role'] === 'student') {
            /* ----------------------------------------------------------
             * 2. LOAD STUDENT RECORD
             * ---------------------------------------------------------- */
            $student = $studentModel->where('user_id', $user['id'])->first();
        
            if (! $student) {
                return redirect()->to('/auth/login')
                    ->with('error', 'Student record not found.');
            }
        
            /* ----------------------------------------------------------
             * 3. SET SESSION FOR STUDENT LOGIN
             * ---------------------------------------------------------- */
            // Get photo from user_photos table first, fallback to Google picture if not found
            $userPhotoModel = new UserPhotoModel();
            $userPhoto = $userPhotoModel->where('user_id', $user['id'])->first();
            $photoUrl = null;
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $photoUrl = base_url($userPhoto['photo_path']);
            } else {
                // Use Google picture only if no photo in database
                $photoUrl = $googleUser->picture ?? null;
            }
            
            session()->set([
                'isLoggedIn'    => true,
                'role'          => 'student',
                'user_id'       => $user['id'],
                'student_id'    => $student['id'],
                'google_login'  => true,  // Flag to indicate Google login
                'google_token'  => $token['access_token'],  // Store token for logout
        
                // Optional: For UX use only
                'email'         => $googleUser->email,
                'name'          => $googleUser->name,
                'photo'         => $photoUrl
            ]);
        
            return redirect()->to('/student/dashboard');
        } elseif ($user['role'] === 'organization') {
            /* ----------------------------------------------------------
             * 2. LOAD ORGANIZATION RECORD
             * ---------------------------------------------------------- */
            $organization = $organizationModel->where('user_id', $user['id'])->first();
        
            if (! $organization) {
                return redirect()->to('/auth/login')
                    ->with('error', 'Organization record not found.');
            }
        
            /* ----------------------------------------------------------
             * 3. SET SESSION FOR ORGANIZATION LOGIN
             * ---------------------------------------------------------- */
            // Get photo from user_photos table first, fallback to Google picture if not found
            $userPhotoModel = new UserPhotoModel();
            $userPhoto = $userPhotoModel->where('user_id', $user['id'])->first();
            $photoUrl = null;
            if ($userPhoto && !empty($userPhoto['photo_path'])) {
                $photoUrl = base_url($userPhoto['photo_path']);
            } else {
                // Use Google picture only if no photo in database
                $photoUrl = $googleUser->picture ?? null;
            }
            
            session()->set([
                'isLoggedIn'           => true,
                'role'                 => 'organization',
                'user_id'              => $user['id'],
                'organization_id'      => $organization['id'],
                'organization_name'    => $organization['organization_name'],
                'organization_acronym' => $organization['organization_acronym'],
                'email'                => $googleUser->email,
                'photo'                => $photoUrl,
                'google_login'         => true,  // Flag to indicate Google login
                'google_token'         => $token['access_token']  // Store token for logout
            ]);
        
            return redirect()->to('/organization/dashboard');
        } else {
            return redirect()->to('/auth/login')
                ->with('error', 'This Google account is not registered as a student or organization.');
        }
    }
    
    /**
     * Google Logout - Revoke token and sign out from Google
     */
    public function googleLogout()
    {
        $session = session();
        $googleToken = $session->get('google_token');
        
        // Revoke Google token if exists (disconnects app from Google account)
        if ($googleToken) {
            try {
                $config = new Google();
                $client = new \Google\Client();
                $client->setClientId($config->clientID);
                $client->setClientSecret($config->clientSecret);
                
                // Disable SSL verification locally
                $httpClient = new GuzzleClient(['verify' => false]);
                $client->setHttpClient($httpClient);
                
                // Revoke the token - this forces re-authentication next time
                $client->revokeToken($googleToken);
            } catch (\Exception $e) {
                // Token might already be expired/revoked, continue with logout
                log_message('debug', 'Google token revoke error: ' . $e->getMessage());
            }
        }
        
        // Clear all session data
        $session->remove(['isLoggedIn', 'role', 'user_id', 'student_id', 'organization_id', 'email', 'name', 'photo', 'google_login', 'google_token']);
        $session->destroy();
        
        // Start new session for flash message
        $session = \Config\Services::session();
        $session->setFlashdata('success', 'You have been logged out from Google successfully.');
        
        // Redirect to login page
        return redirect()->to(base_url('auth/login'));
    }

}

