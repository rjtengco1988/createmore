<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = "Create More";
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('dashboard', $data);
        echo view('common/admin_footer', $data);
    }

    public function testTamperedToken()
    {
        // Set a fake, invalid JWT to simulate a tampered session
        session()->set('id_token', '123123lkj09812kl3123-123123213jkjkl123');
        return redirect()->to('/a/dashboard');
    }
}
