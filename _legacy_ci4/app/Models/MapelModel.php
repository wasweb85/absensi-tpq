<?php

namespace App\Models;

use CodeIgniter\Model;

class MapelModel extends Model
{
    protected $table            = 'tb_mapel';
    protected $primaryKey       = 'id_mapel';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_mapel'];

    public function getAllMapel()
    {
        return $this->findAll();
    }
}
