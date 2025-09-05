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
            return redirect()->to('a/exception-500/');
        } catch (DataException $e) {
            log_message('error', sprintf(
                "Data Error: %s in %s on line %d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            return redirect()->to('a/exception-500/');
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
                    return redirect()->to('a/exception-500/');
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


            try {

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

                $data['roleInformation'] = $this->roles_model->findById($lastInsertedId);

                if ($data['roleInformation']) {
                    $session->remove('roleDefinition');
                    return redirect()->to(base_url('a/role-information/' . $data['roleInformation']['id']))->with('success', 'Role created and permissions attached.');
                } else {
                    return redirect()->back()->withInput()->with('error', 'No Role Definition Found.');
                }
            } catch (DatabaseException $e) {
                log_message('error', sprintf(
                    "Database Error: %s in %s on line %d",
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ));
                return redirect()->to('a/exception-500/');
            } catch (DataException $e) {
                log_message('error', sprintf(
                    "Data Error: %s in %s on line %d",
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                ));
                return redirect()->to('a/exception-500/');
            }
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

    public function roleInformation($id)
    {
        $data = [
            'title' => 'Create More',
        ];

        try {

            $data['rolesInformation'] = $this->roles_model->findById($id);
            $data['noAttachedPermission'] = $this->roles_permission->countPermissionsAttached($id);
            $data['attachedPermissions'] = $this->roles_permission->findPermissionsByNameAndId($this->request->getPost('search_criteria'), $id);
            $data['pager'] = $this->roles_permission->pager;

            if (empty($data['rolesInformation'])) {
                $error = "No Role Name Found. Please make sure the role name exist.";
                log_message(
                    'info',
                    $error
                );
                return redirect()->to('a/exception-404/');
            }
        } catch (DatabaseException $e) {
            log_message('error', sprintf(
                "Database Error: %s in %s on line %d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            return redirect()->to('a/exception-500/');
        } catch (DataException $e) {
            log_message('error', sprintf(
                "Data Error: %s in %s on line %d",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
            return redirect()->to('a/exception-500/');
        }


        if ($this->request->getMethod() === 'POST') {


            $filterRules = [
                'search_criteria' => [
                    'label' => 'Search Criteria',
                    'rules' => 'required|alpha_numeric_space|max_length[100]',
                ],
            ];


            if ($this->validate($filterRules)) {

                try {
                    $data['attachedPermissions'] = $this->roles_permission->findPermissionsByNameAndId($this->request->getPost('search_criteria'), $id);
                } catch (DatabaseException $e) {
                    log_message('error', sprintf(
                        "Database Error: %s in %s on line %d",
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    ));
                    return redirect()->to('a/exception-500/');
                } catch (DataException $e) {
                    log_message('error', sprintf(
                        "Data Error: %s in %s on line %d",
                        $e->getMessage(),
                        $e->getFile(),
                        $e->getLine()
                    ));
                    return redirect()->to('a/exception-500/');
                }
            } else {

                $data['validation'] = $this->validator;
            }
        }


        echo view('common/admin_header', $data);
        echo view('common/admin_menubar', $data);
        echo view('role_information', $data);
        echo view('common/admin_footer', $data);
    }


    public function permissionsJson($roleId)
    {
        $request = service('request');
        $model   = new \App\Models\RolesPermission();

        // DataTables params
        $draw   = (int) ($request->getPost('draw')   ?? 1);
        $start  = (int) ($request->getPost('start')  ?? 0);
        $length = (int) ($request->getPost('length') ?? 10);
        $search = trim(($request->getPost('search')['value'] ?? ''));

        $order = $request->getPost('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
        $colIdx = (int) ($order['column'] ?? 1);
        $dir    = strtoupper($order['dir'] ?? 'ASC');
        $dir    = $dir === 'DESC' ? 'DESC' : 'ASC';

        // Column map (DataTable columns below will match these indices)
        // 0 = checkbox (not orderable), then:
        $columns = [
            1 => 'acl_permissions.name',
            2 => 'acl_permissions.slug',
            3 => 'acl_permissions.description',
        ];
        $orderBy = $columns[$colIdx] ?? 'acl_permissions.name';

        // counts
        $recordsTotal = $model->countByRole((int)$roleId);

        // filtered builder
        $filterBuilder = $model->baseQueryForRole((int)$roleId);
        if ($search !== '') {
            $filterBuilder->groupStart()
                ->like('acl_permissions.name', $search, 'both', true)
                ->orLike('acl_permissions.slug', $search, 'both', true)
                ->orLike('acl_permissions.description', $search, 'both', true)
                ->groupEnd();
        }
        $recordsFiltered = (clone $filterBuilder)->countAllResults();

        // page rows
        $rows = $filterBuilder
            ->orderBy($orderBy, $dir)
            ->limit($length, $start)
            ->get()->getResultArray();

        $data = [];
        foreach ($rows as $r) {
            $data[] = [
                // raw HTML for the checkbox:
                'checkbox'    => '<input type="checkbox" class="row-check form-check-input" name="ids[]" value="' . $r['permission_id'] . '">',
                'name'        => $r['permission_name'] ?? '',
                'slug'        => $r['permission_slug'] ?? '',
                'description' => $r['permission_description'] ?? '',
            ];
        }

        return $this->response->setJSON([
            'draw'            => $draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
            // hand back a fresh token so JS can keep CSRF in sync
            'csrfToken'       => csrf_hash(),
        ]);
    }
}
