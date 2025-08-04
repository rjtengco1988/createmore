<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Can Add Role',
                'slug' => 'can_add_role',
                'description' => 'Allows the user to create and assign new roles',
                'created_at'       => '2025-08-04 12:46:00',
            ],
            [
                'name' => 'Can View Role',
                'slug' => 'can_view_role',
                'description' => 'Allows the user to view the list and details of existing roles',
                'created_at'       => '2025-08-04 12:46:00',
            ],
            [
                'name' => 'Can Modify Role',
                'slug' => 'can_modify_role',
                'description' => 'Allows the user to edit the name, permissions, or settings of existing roles',
                'created_at'       => '2025-08-04 12:46:00',
            ],
            [
                'name' => 'Can Delete Role',
                'slug' => 'can_delete_role',
                'description' => 'Allows the user to remove existing roles from the system',
                'created_at'       => '2025-08-04 12:46:00',
            ],

        ];
        $this->db->table('acl_permissions')->truncate();
        $this->db->table('acl_permissions')->insertBatch($data);
    }
}
