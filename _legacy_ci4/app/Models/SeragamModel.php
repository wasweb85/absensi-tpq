<?php

namespace App\Models;

use CodeIgniter\Model;

class SeragamModel extends Model
{
    protected $table            = 'tb_seragam';
    protected $primaryKey       = 'id_seragam';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'hari', 'nama_seragam', 'deskripsi', 'keterangan'
    ];

    public function getAllByHari()
    {
        $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $rows = $this->orderBy('FIELD(hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')->findAll();

        $result = [];
        foreach ($daysOrder as $day) {
            $result[$day] = null;
        }
        foreach ($rows as $row) {
            $result[$row['hari']] = $row;
        }
        return $result;
    }
}
