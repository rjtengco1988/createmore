<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Error extends BaseController
{
    public function exception404()
    {
        $data['title'] = "Something Went Wrong";
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('exception404', $data);
        echo view('common/admin_footer', $data);
    }

    public function exception500()
    {
        $data['title'] = "Something Went Wrong";
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('exception500', $data);
        echo view('common/admin_footer', $data);
    }
}
