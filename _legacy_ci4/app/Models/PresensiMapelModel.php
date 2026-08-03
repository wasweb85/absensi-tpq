<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiMapelModel extends Model
{
    protected $table            = 'tb_presensi_mapel';
    protected $primaryKey       = 'id_presensi_mapel';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_jadwal_pelajaran', 'id_siswa', 'tanggal', 'id_kehadiran', 'jam_scan', 'keterangan'];

    public function getRekapPerMapel($idJadwalPelajaran)
    {
        return $this->select('id_siswa, SUM(IF(id_kehadiran=1, 1, 0)) as hadir, SUM(IF(id_kehadiran=2, 1, 0)) as sakit, SUM(IF(id_kehadiran=3, 1, 0)) as izin, SUM(IF(id_kehadiran=4, 1, 0)) as alfa')
                    ->where('id_jadwal_pelajaran', $idJadwalPelajaran)
                    ->groupBy('id_siswa')
                    ->findAll();
    }
    
    public function getTotalPertemuan($idJadwalPelajaran)
    {
        return $this->select('COUNT(DISTINCT tanggal) as total')
                    ->where('id_jadwal_pelajaran', $idJadwalPelajaran)
                    ->first()['total'] ?? 0;
    }
}
