<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiSiswa extends Model
{
    protected $table = 'tb_presensi_siswa';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function kehadiran()
    {
        return $this->belongsTo(Kehadiran::class, 'id_kehadiran', 'id_kehadiran');
    }
}
