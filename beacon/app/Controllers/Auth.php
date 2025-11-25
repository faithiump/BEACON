<?php

namespace App\Controllers;
use Config\Google;
use GuzzleHttp\Client as GuzzleClient;

require_once APPPATH . '../vendor/autoload.php';

use App\Models\UserModel;
use App\Models\UserProfileModel;
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
        // Handle login logic here
        $validation = \Config\Services::validation();
        
        $rules = [
            'role' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Add your authentication logic here
        // For now, just redirect to home
        return redirect()->to('/')->with('success', 'Login successful!');
    }

    public function processRegister()
    {
        // Handle registration logic here
        $validation = \Config\Services::validation();
        
        // Role is automatically set to 'student' for register page
        $role = 'student';
        
        $rules = [
            'firstname' => 'required|min_length[2]',
            'lastname' => 'required|min_length[2]',
            'birthday' => 'required|valid_date',
            'gender' => 'required',
            'phone' => 'required',
            'region' => 'required|min_length[3]',
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
            'in_organization' => 'required|in_list[yes,no]'
        ];
        
        // If student is in organization, require organization name
        if ($this->request->getPost('in_organization') === 'yes') {
            $rules['organization_name'] = 'required|min_length[3]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Start database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Create address record
            $addressData = [
                'region' => $this->request->getPost('region'),
                'city_municipality' => $this->request->getPost('city_municipality'),
                'barangay' => $this->request->getPost('barangay')
            ];
            $addressId = $this->addressModel->insert($addressData);

            // 2. Create user account
            $userData = [
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'), // Will be hashed by model
                'role' => 'student',
                'is_active' => 1,
                'email_verified' => 0
            ];
            $userId = $this->userModel->insert($userData);

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
            $this->userProfileModel->insert($profileData);

            // 4. Create student record
            $studentData = [
                'user_id' => $userId,
                'student_id' => $this->request->getPost('student_id'),
                'department' => $this->request->getPost('department'),
                'course' => $this->request->getPost('course'),
                'year_level' => $this->request->getPost('year_level'),
                'in_organization' => $this->request->getPost('in_organization'),
                'organization_name' => $this->request->getPost('in_organization') === 'yes' 
                    ? $this->request->getPost('organization_name') 
                    : null
            ];
            $this->studentModel->insert($studentData);

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('errors', ['Database error occurred. Please try again.']);
            }

            return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('errors', ['An error occurred during registration. Please try again.']);
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
    $userModel    = new \App\Models\UserModel();
    $studentModel = new \App\Models\StudentModel();

    /* ----------------------------------------------------------
     * 1. CHECK USERS TABLE BY EMAIL
     * ---------------------------------------------------------- */
    $user = $userModel->where('email', $googleUser->email)->first();

    if (! $user) {
        return redirect()->to('/auth/login')
            ->with('error', 'Your Google account is not registered in the system.');
    }

    // User exists but MUST be student
    if ($user['role'] !== 'student') {
        return redirect()->to('/auth/login')
            ->with('error', 'This Google account is not registered as a student.');
    }

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
    session()->set([
        'isLoggedIn' => true,
        'role'       => 'student',
        'user_id'    => $user['id'],
        'student_id' => $student['id'],

        // Optional: For UX use only
        'email'      => $googleUser->email,
        'name'       => $googleUser->name,
        'photo'      => $googleUser->picture
    ]);

    return redirect()->to('/student/dashboard');
}


}

