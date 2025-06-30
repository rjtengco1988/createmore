<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function index()
    {
        $data['title'] = "Create More";
        echo view('login', $data);
    }

    public function privacyPolicy()
    {
        $data['title'] = "Privacy and Policy";
        echo view('privacy_policy', $data);
    }

    public function termsAndConditions()
    {
        echo "terms and conditions";
    }
}
