<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePresensiMapelTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_presensi_mapel' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_jadwal_pelajaran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_siswa' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'tanggal' => [
                'type'       => 'DATE',
            ],
            'id_kehadiran' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jam_scan' => [
                'type'       => 'TIME',
                'null'       => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_presensi_mapel', true);
        
        $this->forge->addForeignKey('id_jadwal_pelajaran', 'tb_jadwal_pelajaran', 'id_jadwal_pelajaran', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_siswa', 'tb_siswa', 'id_siswa', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_kehadiran', 'tb_kehadiran', 'id_kehadiran', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tb_presensi_mapel', true);
    }

    public function down()
    {
        $this->forge->dropTable('tb_presensi_mapel');
    }
}
