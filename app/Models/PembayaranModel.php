<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'tbl_pembayaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_anggota', 'tanggal', 'total_bayar', 'status', 'id_admin', 'tipe_pembayaran', 'jatuh_tempo', 'total_sewa', 'total_biaya_tambahan', 'id_kamar', 'tanggal_mulai_sewa'];

    public function getAllPembayaranAnggota()
    {
        return $this->table('tbl_anggota')->select('tbl_anggota.*')->orderBy('tbl_anggota.nama', 'asc');
    }
}
