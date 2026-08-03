<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'tb_mapel';
    protected $primaryKey = 'id_mapel';
    public $timestamps = false;
    protected $guarded = [];
}
