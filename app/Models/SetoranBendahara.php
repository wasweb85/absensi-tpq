<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetoranBendahara extends Model
{
    protected $table = 'tb_setoran_bendahara';
    protected $primaryKey = 'id_setoran';

    protected $fillable = [
        'id_guru',
        'id_bendahara',
        'tanggal',
        'nominal',
        'keterangan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'id_bendahara', 'id');
    }

    public function tabungan()
    {
        return $this->hasMany(Tabungan::class, 'id_setoran', 'id_setoran');
    }
}
