<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RolesTableAlterCreatedBy extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('acl_roles', [
            'created_by' => [
                'name'       => 'created_by',
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('acl_roles', [
            'created_by' => ['type' => 'TIMESTAMP', 'null' => false],
        ]);
    }
}
