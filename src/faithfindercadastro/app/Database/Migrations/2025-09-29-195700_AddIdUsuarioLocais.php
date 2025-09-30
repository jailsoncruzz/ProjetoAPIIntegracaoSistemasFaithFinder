<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToLocais extends Migration
{
    public function up()
    {
        $this->forge->addColumn('locais', [
            'fk_user_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => false,
                'after'      => 'id',
            ],
            'CONSTRAINT locais_user_id_foreign FOREIGN KEY(fk_user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE'
        ]);
    }

    public function down()
    {
        $this->forge->dropForeignKey('locais', 'locais_user_id_foreign');
        $this->forge->dropColumn('locais', 'fk_user_id');
    }
}