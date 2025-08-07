<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RolesTableAddUpdatedByField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('acl_roles', [
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'created_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('acl_roles', 'updated_by');
    }
}
