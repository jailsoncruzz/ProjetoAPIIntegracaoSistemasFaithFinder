<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeLocaisCollation extends Migration
{
    /**
     * Esta função 'up' é executada quando rodamos o comando 'migrate'.
     * Ela aplica as nossas mudanças.
     */
    public function up()
    {
        $fields = [
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'collate'    => 'utf8mb4_unicode_ci', // Collation corrigido
            ],
            'descricao' => [
                'type'    => 'TEXT',
                'null'    => true,
                'collate' => 'utf8mb4_unicode_ci', // Collation corrigido
            ],
            'cidade' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'collate'    => 'utf8mb4_unicode_ci', // Collation corrigido
            ],
            'bairro' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'collate'    => 'utf8mb4_unicode_ci', // Collation corrigido
            ],
            'rua' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'collate'    => 'utf8mb4_unicode_ci', // Collation corrigido
            ],
        ];

        // Aplica a modificação na tabela 'locais'
        $this->forge->modifyColumn('locais', $fields);
    }

    /**
     * Esta função 'down' é executada para reverter a migration.
     * É uma boa prática defini-la.
     */
    public function down()
    {
        // Reverte para um collation genérico (ou o que estava antes, se você souber)
        $fields = [
            'nome' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'collate' => 'utf8mb4_general_ci'],
            'descricao' => ['type' => 'TEXT', 'null' => true, 'collate' => 'utf8mb4_general_ci'],
            'cidade' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'collate' => 'utf8mb4_general_ci'],
            'bairro' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'collate' => 'utf8mb4_general_ci'],
            'rua' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false, 'collate' => 'utf8mb4_general_ci'],
        ];
        
        $this->forge->modifyColumn('locais', $fields);
    }
}