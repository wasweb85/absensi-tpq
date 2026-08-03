<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJadwalTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jadwal_pelajaran' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_mapel' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_guru' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'id_kelas' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'hari' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'jam_mulai' => [
                'type'       => 'TIME',
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
        $this->forge->addKey('id_jadwal_pelajaran', true);
        
        $this->forge->addForeignKey('id_mapel', 'tb_mapel', 'id_mapel', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_guru', 'tb_guru', 'id_guru', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_kelas', 'tb_kelas', 'id_kelas', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tb_jadwal_pelajaran', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_jadwal_pelajaran');
    }
}
