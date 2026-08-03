<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'tb_kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;
    protected $guarded = [];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_wali_kelas', 'id_guru');
    }
}
