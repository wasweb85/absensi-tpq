<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'tb_guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;
    protected $guarded = [];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_wali_kelas', 'id_guru');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiGuru::class, 'id_guru', 'id_guru');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id_guru', 'id_guru');
    }
}
