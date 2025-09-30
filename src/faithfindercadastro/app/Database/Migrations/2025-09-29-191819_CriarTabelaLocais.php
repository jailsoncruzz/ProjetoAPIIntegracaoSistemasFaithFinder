<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CriarTabelaLocais extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'tipo' => ['type' => 'ENUM', 'constraint' => ['igreja', 'evento'], 'default' => 'igreja'],
            'nome' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'descricao' => ['type' => 'TEXT', 'null' => true],
            'data_referencia' => ['type' => 'DATETIME', 'null' => true],
            'cep' => ['type' => 'VARCHAR', 'constraint' => '9'],
            'estado' => ['type' => 'VARCHAR', 'constraint' => '2'],
            'cidade' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'bairro' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'rua' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'numero' => ['type' => 'VARCHAR', 'constraint' => '20'],
            'complemento' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => true],
            'latitude' => ['type' => 'DECIMAL', 'constraint' => '10,8', 'null' => true],
            'longitude' => ['type' => 'DECIMAL', 'constraint' => '11,8', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('locais');
    }

    public function down()
    {
        $this->forge->dropTable('locais');
    }
}