<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeragamTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_seragam' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'nama_seragam' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'deskripsi' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_seragam', true);
        $this->forge->createTable('tb_seragam', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_seragam');
    }
}
