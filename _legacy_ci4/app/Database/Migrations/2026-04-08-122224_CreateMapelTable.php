<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMapelTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_mapel' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_mapel' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id_mapel', true);
        $this->forge->createTable('tb_mapel', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_mapel');
    }
}
