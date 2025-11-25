<?php 
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Google extends BaseConfig
{
    public $clientID     = '1090988558852-69skspffgsp89h7rftuoacv5lfhglerk.apps.googleusercontent.com';
    public $clientSecret = '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';
    public $redirectUri  = 'http://localhost:8080/auth/googleCallback';
}
