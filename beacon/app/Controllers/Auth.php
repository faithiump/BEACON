<?php

namespace App\Controllers;
use Config\Google;
use GuzzleHttp\Client as GuzzleClient;

require_once APPPATH . '../vendor/autoload.php';

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
    
        // Store info in session
        session()->set([
            'isLoggedIn' => true,
            'email'      => $googleUser->email,
            'name'       => $googleUser->name,
            'photo'      => $googleUser->picture
        ]);
    
        return redirect()->to('/');
    }
    

}

