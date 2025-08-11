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


    public function createRole()
    {
        helper(['form']);
        $data['title'] = "Create More";
        $data['validation'] = null;


        if ($this->request->getMethod() == "POST") {
            $filterRules = [
                'roleName' => [
                    'label' => 'Role Name',
                    'rules' => 'required|max_length[128]|regex_match[/^[A-Za-z0-9 +=,.@-]+$/]',
                ],

                'roleSlug' => [
                    'label' => 'Role Slug',
                    'rules' => 'required|max_length[128]|regex_match[/^[a-z0-9-]+$/]|is_unique[acl_roles.slug]',
                    'errors' => [
                        'alpha_dash' => 'The {field} may only contain lowercase letters, numbers, and hyphens.',
                        'lowercase'  => 'The {field} must be in lowercase.',
                        'is_unique'  => 'The {field} must be unique.',
                    ]
                ],
                'roleDescription' => [
                    'label' => 'Role Description',
                    'rules' => 'permit_empty|max_length[200]',
                    'errors' => [
                        'max_length' => 'The {field} must be 200 characters or fewer.'
                    ]
                ],
            ];

            if ($this->validate($filterRules)) {

                $data = [
                    'name'   => $this->request->getPost('roleName'),
                    'slug' => $this->request->getPost('roleSlug'),
                    'description' => $this->request->getPost('roleDescription'),
                    'created_by' => session()->get('user_email')
                ];

                if ($this->roles_model->insertRole($data)) {
                    return redirect()->to('a/roles/attach-permissions')->with('success', 'Role added successfully.');
                } else {
                    return redirect()->back()->with('error', 'Failed to add role definition.');
                }

                $this->roles_model->insertRole($data);
            } else {
                $data['validation'] = $this->validator;
            }
        }
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('create_role', $data);
        echo view('common/admin_footer', $data);
    }
}
