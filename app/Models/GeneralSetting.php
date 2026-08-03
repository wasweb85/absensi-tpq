<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'logo',
        'school_name',
        'school_year',
        'copyright'
    ];
}
