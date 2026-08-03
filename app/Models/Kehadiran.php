<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'tb_kehadiran';
    protected $primaryKey = 'id_kehadiran';
    public $timestamps = false;
    protected $guarded = [];

    // Constants for magic numbers
    public const HADIR = 1;
    public const SAKIT = 2;
    public const IZIN = 3;
    public const ALPHA = 4;

    public function presensiSiswa()
    {
        return $this->hasMany(PresensiSiswa::class, 'id_kehadiran', 'id_kehadiran');
    }

    public function presensiGuru()
    {
        return $this->hasMany(PresensiGuru::class, 'id_kehadiran', 'id_kehadiran');
    }
}
