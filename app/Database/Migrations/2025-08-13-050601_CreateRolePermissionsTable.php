<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolePermissionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role_id'    => ['type' => 'INT', 'constraint' => '100'],
            'permission_id'    => ['type' => 'INT', 'constraint' => '100'],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true
            ],
            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'deleted_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_by' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('acl_role_permissions');
    }

    public function down()
    {
        $this->forge->dropTable('acl_role_permissions');
    }
}
