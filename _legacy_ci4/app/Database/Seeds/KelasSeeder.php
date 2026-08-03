<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['jilid' => '1', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => '2', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => '3', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => '4', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => '5', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => '6', 'kategori' => 'Campur', 'index_kelas' => 'A'],
            ['jilid' => 'Al-Quran', 'kategori' => 'Putra', 'index_kelas' => 'A'],
            ['jilid' => 'Al-Quran', 'kategori' => 'Putri', 'index_kelas' => 'A'],
        ];

        // Using Query Builder for batch insert
        $this->db->table('tb_kelas')->insertBatch($data);
    }
}