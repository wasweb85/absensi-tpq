<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiGuru extends Model
{
    protected $table = 'tb_presensi_guru';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kehadiran()
    {
        return $this->belongsTo(Kehadiran::class, 'id_kehadiran', 'id_kehadiran');
    }
}
