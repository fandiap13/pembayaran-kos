<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembayaranModel extends Model
{
    protected $table            = 'tbl_detail_pembayaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_pembayaran', 'tanggal', 'bayar', 'id_admin'];

    public function totalDibayar($id_pembayaran)
    {
        $result = $this->db->table($this->table)->where('id_pembayaran', $id_pembayaran)->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }
}
