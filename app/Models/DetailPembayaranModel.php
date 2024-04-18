<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPembayaranModel extends Model
{
    protected $table            = 'tbl_detail_pembayaran';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['id_pembayaran', 'tanggal', 'bayar', 'id_admin', 'id_anggota', 'tipe_pembayaran', 'keterangan'];

    public function allTotalBayarKos($tahun, $tipe = null)
    {
        if ($tipe == null) {
            $result = $this->db->table($this->table)
                ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
                ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
                ->get()->getResultArray();
        } else {
            $result = $this->db->table($this->table)
                ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
                ->where($this->table . '.tipe_pembayaran', $tipe)
                ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
                ->get()->getResultArray();
        }
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayar($id_pembayaran)
    {
        $result = $this->db->table($this->table)->where('id_pembayaran', $id_pembayaran)->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayarTunai($id_pembayaran)
    {
        $result = $this->db->table($this->table)
            ->where('id_pembayaran', $id_pembayaran)
            ->where('tipe_pembayaran', 'tunai')
            ->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayarTransfer($id_pembayaran)
    {
        $result = $this->db->table($this->table)
            ->where('id_pembayaran', $id_pembayaran)
            ->where('tipe_pembayaran', 'transfer')
            ->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayarByIDAnggota($id_anggota, $tahun, $tipe = null)
    {
        if ($tipe != null) {
            $result = $this->db->table($this->table)
                ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
                ->where('tbl_detail_pembayaran.id_anggota', $id_anggota)
                ->where('tbl_detail_pembayaran.tipe_pembayaran', $tipe)
                ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
                ->get()->getResultArray();
        } else {
            $result = $this->db->table($this->table)
                ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
                ->where('tbl_detail_pembayaran.id_anggota', $id_anggota)
                ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
                ->get()->getResultArray();
        }
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayarByIDAnggotaTransfer($id_anggota, $tahun)
    {
        $result = $this->db->table($this->table)
            ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
            ->where('tbl_detail_pembayaran.id_anggota', $id_anggota)
            ->where('tbl_detail_pembayaran.tipe_pembayaran', 'transfer')
            ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
            ->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }

    public function totalDibayarByIDAnggotaTunai($id_anggota, $tahun)
    {
        $result = $this->db->table($this->table)
            ->join('tbl_pembayaran', "tbl_pembayaran.id=tbl_detail_pembayaran.id_pembayaran")
            ->where('tbl_detail_pembayaran.id_anggota', $id_anggota)
            ->where('tbl_detail_pembayaran.tipe_pembayaran', 'tunai')
            ->where('DATE_FORMAT(jatuh_tempo, "%Y")', $tahun)
            ->get()->getResultArray();
        $total = 0;
        foreach ($result as $r) {
            $total += intval($r['bayar']);
        }
        return $total;
    }
}
