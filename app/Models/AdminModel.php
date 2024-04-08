<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table            = 'tbl_admin';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['username', 'password', 'telp', 'nama'];
}
