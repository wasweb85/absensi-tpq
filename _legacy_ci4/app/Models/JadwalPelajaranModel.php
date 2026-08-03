<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPelajaranModel extends Model
{
    protected $table            = 'tb_jadwal_pelajaran';
    protected $primaryKey       = 'id_jadwal_pelajaran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kelas', 'id_mapel', 'id_guru', 'hari', 'jam_mulai', 'jam_selesai', 'keterangan'
    ];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getAll()
    {
        return $this->select('tb_jadwal_pelajaran.*, 
                              m.nama_mapel,
                              g.nama_guru,
                              k.jilid, k.index_kelas, k.kategori')
                    ->join('tb_mapel m', 'm.id_mapel = tb_jadwal_pelajaran.id_mapel', 'left')
                    ->join('tb_guru g', 'g.id_guru = tb_jadwal_pelajaran.id_guru', 'left')
                    ->join('tb_kelas k', 'k.id_kelas = tb_jadwal_pelajaran.id_kelas', 'left')
                    ->orderBy('FIELD(tb_jadwal_pelajaran.hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')
                    ->orderBy('tb_jadwal_pelajaran.jam_mulai', 'ASC')
                    ->findAll();
    }

    public function getByKelas($idKelas)
    {
        return $this->select('tb_jadwal_pelajaran.*, 
                              m.nama_mapel,
                              g.nama_guru,
                              k.jilid, k.index_kelas, k.kategori')
                    ->join('tb_mapel m', 'm.id_mapel = tb_jadwal_pelajaran.id_mapel', 'left')
                    ->join('tb_guru g', 'g.id_guru = tb_jadwal_pelajaran.id_guru', 'left')
                    ->join('tb_kelas k', 'k.id_kelas = tb_jadwal_pelajaran.id_kelas', 'left')
                    ->where('tb_jadwal_pelajaran.id_kelas', $idKelas)
                    ->orderBy('FIELD(tb_jadwal_pelajaran.hari, "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu")')
                    ->orderBy('tb_jadwal_pelajaran.jam_mulai', 'ASC')
                    ->findAll();
    }
}
