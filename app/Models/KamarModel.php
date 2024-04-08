<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table            = 'tbl_kamar';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'spesifikasi', 'harga', 'id_kategori', 'lantai'];

    public function getKamarTersedia($id_anggota = null)
    {
        // active dijadikan 0
        if ($id_anggota == null) {
            return $this->db->table($this->table)->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota')
                ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
                ->where('tbl_anggota.id IS NULL')
                ->orWhereIn('tbl_anggota.active', [0, null])
                ->get()->getResultArray();
        }
        // menampilkan kamar yang belum terdaftar
        return $this->db->table($this->table)->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota')
            ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
            ->where('tbl_anggota.nama IS NULL')
            ->orWhere('tbl_anggota.id', $id_anggota)
            // ->orWhereIn('tbl_anggota.active', [0, null])
            ->get()->getResultArray();
    }
}
