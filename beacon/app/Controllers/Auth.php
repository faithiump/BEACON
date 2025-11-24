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
        
        $rules = [
            'fullname' => 'required|min_length[3]',
            'role' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Add your registration logic here
        // For now, just redirect to login
        return redirect()->to('/auth/login')->with('success', 'Registration successful! Please login.');
    }
}

