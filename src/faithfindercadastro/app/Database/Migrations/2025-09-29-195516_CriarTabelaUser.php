<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'google_id'  => ['type' => 'VARCHAR', 'constraint' => '255', 'unique' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => '255'],
            'name'       => ['type' => 'VARCHAR', 'constraint' => '255'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}