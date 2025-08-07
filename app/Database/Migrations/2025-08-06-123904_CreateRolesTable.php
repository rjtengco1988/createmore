<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
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
            'slug'       => ['type' => 'VARCHAR', 'constraint' => '100'],
            'description'    => ['type' => 'TEXT'],
            'created_by' => ['type' => 'TIMESTAMP', 'null' => false],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true
            ],
            'updated_at'  => ['type' => 'TIMESTAMP', 'null' => true],
            'deleted_at'  => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('acl_roles');
    }

    public function down()
    {
        $this->forge->dropTable('acl_roles');
    }
}
