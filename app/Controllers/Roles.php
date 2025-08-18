<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RolesModel;
use App\Models\Permissions_Model;
use App\Models\RolesPermission;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\ResponseInterface;

class Roles extends BaseController
{
    private $roles_model;
    private $permission_model;
    private $roles_permission;

    public function __construct()
    {
        $this->request = \Config\Services::request();
        $this->roles_model = new RolesModel();
        $this->permission_model = new Permissions_Model();
        $this->roles_permission = new RolesPermission();
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

                try {
                    $roleDefinition = [
                        'name'        => $this->request->getPost('roleName'),
                        'slug'        => $this->request->getPost('roleSlug'),
                        'description' => $this->request->getPost('roleDescription'),
                    ];

                    session()->set('roleDefinition', $roleDefinition);

                    return redirect()
                        ->to('a/roles/attach-permissions')
                        ->with('success', 'Role added successfully.');
                } catch (\Throwable $e) {
                    log_message('error', sprintf(
                        "Data Error: %s in %s on line %d",
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    ));
                    return redirect()
                        ->back()
                        ->with('error', $e->getMessage());
                }
            } else {
                $data['validation'] = $this->validator;
            }
        }
        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('create_role', $data);
        echo view('common/admin_footer', $data);
    }


    public function attachPermissions()
    {
        $session = session();
        $roleDef = $session->get('roleDefinition'); // ['name','slug','description'] from Step 1

        // Guard both GET and POST
        if (empty($roleDef)) {
            return redirect()->to(base_url('a/roles/create'))
                ->with('error', 'Start by creating a role.');
        }

        // POST = user clicked "Attach"
        if ($this->request->getMethod() === 'POST') {
            // Read selected permission IDs
            $permIds = array_map('intval', (array) $this->request->getPost('permissions'));
            $permIds = array_values(array_unique(array_filter($permIds, fn($v) => $v > 0)));

            // Optional: require at least one
            if (empty($permIds)) {
                return redirect()->back()->withInput()->with('error', 'Select at least one permission.');
            }


            $validIds  = $this->permission_model->findPermissionsById($permIds);
            if (count($validIds) !== count($permIds)) {
                return redirect()->back()->withInput()->with('error', 'Some permissions are invalid.');
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $this->roles_model->insertRole([
                'name'        => $roleDef['name'],
                'slug'        => $roleDef['slug'],
                'description' => $roleDef['description'],
                'created_by' => session()->get('user_email')
            ]);

            $lastInsertedId = $db->insertID();


            // Attach permissions in pivot table
            if (!empty($validIds)) {

                $this->roles_permission->insertRolePermission($lastInsertedId, $validIds, session()->get('user_email'), true);
            }

            $db->transComplete();
            if (! $db->transStatus()) {
                return redirect()->back()->withInput()->with('error', 'Could not save role and permissions.');
            }

            // // Clear wizard/session so it doesn’t linger
            $session->remove('roleDefinition');

            return redirect()->to(base_url('a/roles/attached-to-user/'))->with('success', 'Role created and permissions attached.');
        }

        // GET = render page
        helper(['form']);
        $data = [
            'title' => 'Create More',
            'attachedPermissionIds'  => [],
            'roleDefinition' => $roleDef,
        ];

        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('attach_permissions', $data);
        echo view('common/admin_footer', $data);
    }
}
