<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table            = 'tbl_anggota';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama', 'tgl_lahir', 'telp', 'telp_kerabat', 'alamat', 'tgl_kost', 'biaya_tambahan', 'keterangan', 'status', 'id_kamar', 'jenis_sewa', 'active', 'tanggal_tidak_aktif'];

    public function getAllAnggota($id = null)
    {
        if ($id != null) {
            return $this->table($this->table)
                ->select($this->table . '.*,tbl_kamar.nama as kamar,tbl_kamar.lantai,tbl_kamar.harga as harga')
                ->join('tbl_kamar', 'tbl_kamar.id=' . $this->table . '.id_kamar')
                ->where('active', 1)
                ->where($this->table . '.id', $id);
        }
        return $this->table($this->table)
            ->select($this->table . '.*,tbl_kamar.nama as kamar,tbl_kamar.lantai,tbl_kamar.harga as harga')
            ->join('tbl_kamar', 'tbl_kamar.id=' . $this->table . '.id_kamar')
            ->where('active', 1)
            ->orderBy('tbl_kamar.nama', 'asc');
    }

    public function getAllAnggotaTidakAktif($id = null)
    {
        if ($id != null) {
            return $this->table($this->table)
                ->select($this->table . '.*,tbl_kamar.nama as kamar,tbl_kamar.lantai,tbl_kamar.harga as harga')
                ->join('tbl_kamar', 'tbl_kamar.id=' . $this->table . '.id_kamar')
                ->where('active', 0)
                ->where($this->table . '.id', $id);
        }
        return $this->table($this->table)
            ->select($this->table . '.*,tbl_kamar.nama as kamar,tbl_kamar.lantai,tbl_kamar.harga as harga')
            ->join('tbl_kamar', 'tbl_kamar.id=' . $this->table . '.id_kamar')
            ->where('active', 0)
            ->orderBy('tbl_kamar.nama', 'asc');
    }
    
    public function getTotalAnggotaAKtif()
    {
        return $this->table($this->table)
            ->select($this->table . '.*,tbl_kamar.nama as kamar,tbl_kamar.lantai,tbl_kamar.harga as harga')
            ->join('tbl_kamar', 'tbl_kamar.id=' . $this->table . '.id_kamar')
            ->where('active', 1)->get()->getNumRows();
    }
}
