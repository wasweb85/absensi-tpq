<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Siswa extends Authenticatable
{
    protected $table = 'tb_siswa';
    protected $primaryKey = 'id_siswa';
    public $timestamps = false;
    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiSiswa::class, 'id_siswa', 'id_siswa');
    }

    public function tabungan()
    {
        return $this->hasMany(Tabungan::class, 'id_siswa', 'id_siswa');
    }
}
