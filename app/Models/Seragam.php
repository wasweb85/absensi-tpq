<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seragam extends Model
{
    protected $table = 'tb_seragam';
    protected $primaryKey = 'id_seragam';
    public $timestamps = false;
    protected $guarded = [];
}
