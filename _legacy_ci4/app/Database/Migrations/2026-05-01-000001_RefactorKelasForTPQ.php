<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefactorKelasForTPQ extends Migration
{
    public function up()
    {
        // 1. Remove Foreign Key
        // Need to check the actual FK name. Usually it's tb_kelas_id_jurusan_foreign
        try {
            $this->db->query("ALTER TABLE tb_kelas DROP FOREIGN KEY tb_kelas_id_jurusan_foreign");
        } catch (\Throwable $e) {
            // Ignore if the foreign key doesn't exist
        }

        // 2. Rename 'tingkat' to 'jilid'
        $this->forge->modifyColumn('tb_kelas', [
            'tingkat' => [
                'name' => 'jilid',
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
        ]);

        // 3. Add 'kategori' column
        $this->forge->addColumn('tb_kelas', [
            'kategori' => [
                'type' => 'ENUM',
                'constraint' => ['Campur', 'Putra', 'Putri'],
                'default' => 'Campur',
                'after' => 'jilid',
            ],
        ]);

        // 4. Drop 'id_jurusan' column
        $this->forge->dropColumn('tb_kelas', 'id_jurusan');

        // 5. Drop 'tb_jurusan' table
        $this->forge->dropTable('tb_jurusan', true);
    }

    public function down()
    {
        // Not strictly necessary since we are specialized now, but good practice
        // (Skipping complex reversal for now as it's a one-way refactor for TPQ)
    }
}
