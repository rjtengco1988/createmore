<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Permissions extends BaseController
{
    public function index()
    {
        $data['title'] = "Create More";
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('permissions', $data);
        echo view('common/admin_footer', $data);
    }
}
