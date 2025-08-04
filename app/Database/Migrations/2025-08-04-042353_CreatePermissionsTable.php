<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionsTable extends Migration
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
            'name'    => ['type' => 'VARCHAR', 'constraint' => '100'],
            'group'    => ['type' => 'VARCHAR', 'constraint' => '100'],
            'slug'       => ['type' => 'VARCHAR', 'constraint' => '100'],
            'description'    => ['type' => 'TEXT'],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true
            ],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('acl_permissions');
    }

    public function down()
    {
        $this->forge->dropTable('acl_permissions');
    }
}
