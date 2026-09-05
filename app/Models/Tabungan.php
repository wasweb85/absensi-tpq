<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    protected $table = 'tb_tabungan';
    protected $primaryKey = 'id_tabungan';

    protected $fillable = [
        'id_siswa',
        'id_user',
        'tanggal',
        'jenis_transaksi',
        'nominal',
        'keterangan',
        'status_setoran',
        'id_setoran',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    public function setoranBendahara()
    {
        return $this->belongsTo(SetoranBendahara::class, 'id_setoran', 'id_setoran');
    }
}
