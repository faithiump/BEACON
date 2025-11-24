<?php

namespace App\Controllers;

class Auth extends BaseController
{
    protected $helpers = ['url'];

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

        // Add your registration logic here
        // For now, just redirect to login
        return redirect()->to(base_url('auth/login'))->with('success', 'Registration successful! Please login.');
    }
}

