<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'tbl_kategori_kamar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['kategori'];
}
