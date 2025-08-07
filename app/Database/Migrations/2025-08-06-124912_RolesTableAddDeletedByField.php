<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RolesTableAddDeletedByField extends Migration
{
    public function up()
    {
        $this->forge->addColumn('acl_roles', [
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'updated_at',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('acl_roles', 'deleted_by');
    }
}
