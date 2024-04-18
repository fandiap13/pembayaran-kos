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
        // cek anggota aktif atau tidak
        $cekAnggota = $this->db->table('tbl_anggota')->where('id', $id_anggota)->get()->getRowArray();
        // jika anggota aktif maka tidak perlu mengecek kamar saat ini
        if ($cekAnggota['active'] == 1) {
            return $this->db->table($this->table)
                ->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota')
                ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
                ->where('tbl_anggota.nama IS NULL')
                ->orWhere('tbl_anggota.id', $id_anggota)
                // ->orWhereIn('tbl_anggota.active', [0, null])
                ->get()->getResultArray();
        } else {    // jika anggota tidak aktif maka hanya menampilkan data kamar yang benar2 kosongan
            // cek apakah kamar lama anda sudah digunakan oleh anggota aktif
            $cekKamarLama = $this->db->table('tbl_anggota')
                ->where('active', 1)->where('id_kamar', $cekAnggota['id_kamar'])->get()->getRowArray();
            // jika sudah maka kecualikan untuk kamar lama tersebut
            if ($cekKamarLama) {
                // return $cekAnggota['id_kamar'];
                return $this->db->table($this->table)->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota, tbl_anggota.active')
                    ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
                    ->where($this->table . '.id !=', $cekAnggota['id_kamar'])
                    ->where('tbl_anggota.active', 0)
                    ->orWhere('tbl_anggota.active IS NULL')
                    // ->where('tbl_anggota.id IS NULL')
                    ->get()->getResultArray();
            } else {
                return $this->db->table($this->table)
                    ->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota')
                    ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
                    ->where('tbl_anggota.nama IS NULL')
                    ->orWhere('tbl_anggota.id', $id_anggota)
                    // ->orWhereIn('tbl_anggota.active', [0, null])
                    ->get()->getResultArray();
            }
        }
    }

    public function getTotalKamarTersedia()
    {
        return $this->db->table($this->table)
            ->select($this->table . '.*,tbl_anggota.nama as anggota, tbl_anggota.id as id_anggota')
            ->join('tbl_anggota', 'tbl_anggota.id_kamar=' . $this->table . '.id', 'left')
            ->where('tbl_anggota.id IS NULL')
            ->orWhereIn('tbl_anggota.active', [0, null])
            ->get()->getNumRows();
    }
}
