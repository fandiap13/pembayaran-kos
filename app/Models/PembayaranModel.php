<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'tbl_pembayaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_anggota', 'tanggal', 'total_bayar', 'status', 'id_admin', 'tipe_pembayaran', 'jatuh_tempo', 'total_sewa', 'total_biaya_tambahan', 'id_kamar', 'tanggal_mulai_sewa', 'no_pembayaran', 'keterangan', 'diskon', 'keterangan_pembayaran'];

    public function getAllPembayaranAnggota()
    {
        return $this->table('tbl_anggota')->select('tbl_anggota.*')->orderBy('tbl_anggota.nama', 'asc');
    }

    public function getNoPembayaran($id_kamar)
    {
        $db = db_connect();
        $cek = $db->table($this->table)->select('max(no_pembayaran) as no_pembayaran,tbl_kamar.nama')
            ->join("tbl_kamar", $this->table . '.id_kamar=tbl_kamar.id')
            ->where('tbl_kamar.id', $id_kamar)->get()->getRowArray();
        $kamar = $db->table("tbl_kamar")->where('id', $id_kamar)->get()->getRowArray();
        if (!$cek) {
            $no_pembayaran = $kamar['nama'] . '-0001';
        } else {
            $data = $cek['no_pembayaran'];
            $lastnourut = substr($data, -4);
            $nextnourut = intval($lastnourut) + 1;
            $no_pembayaran = $kamar['nama'] . "-" . sprintf('%04s', $nextnourut);
        }
        return $no_pembayaran;
    }
}
