<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\ResponseInterface;

class Roles extends BaseController
{
    private $roles_model;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->roles_model = new RolesModel();
    }

    public function index()
    {
        $data['title'] = "Create More";

        try {

            $data['search_name'] = $this->request->getPost('name');

            if ($this->request->getMethod() == "GET") {

                $data['search_name'] = $this->request->getGet('name');
                $criteria = $this->request->getGet();
                $data['show_all'] = $this->roles_model->findPermissions($criteria);
                $data['pager'] = $this->roles_model->pager;
            } else {
                $data['show_all'] = $this->roles_model->show_all();
                $data['pager'] = $this->roles_model->pager;
            }
        } catch (DatabaseException $e) {
            log_message('error', sprintf(
                "Database Error: %s in %s on line %d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            $data['error'] = "A database error occurred. Please try again later.";
        } catch (DataException $e) {
            log_message('error', sprintf(
                "Data Error: %s in %s on line %d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            $data['error'] = "A data error occurred. Please try again later.";
        }
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('roles', $data);
        echo view('common/admin_footer', $data);
    }
}
