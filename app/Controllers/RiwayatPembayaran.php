<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\DetailPembayaranModel;
use App\Models\PembayaranModel;
use Hermawan\DataTables\DataTable;

class RiwayatPembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $detailPembayaranModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPembayaranModel = new DetailPembayaranModel();
        $this->anggotaModel = new AnggotaModel();
    }

    public function index()
    {
        return view('riwayat_pembayaran/v_index', [
            'title' => "Riwayat Pembayaran Kos",
        ]);
    }


    function hitungJatuhTempo($tgl_kost, $current_month)
    {
        $arr_mulai = explode("-", $tgl_kost);
        $arr_today = explode("-", $current_month);
        $jatuh_tempo = date("Y-m-d", strtotime($arr_today[0] . "-" . $arr_today[1] . "-" . $arr_mulai[2]));
        return $jatuh_tempo;
    }

    function cekPembayaranPerBulan($row, $bulan)
    {
        $tiga_bulan = ' +3 months';
        $perbulan = " +1 months";
        $setahun = ' +1 year';

        $bulan_saat_ini = $bulan;
        // cek apakah ada transaksi bulan ini ?
        $cekTransaksiTahun = $this->pembayaranModel->select("tbl_pembayaran.*,tbl_kamar.nama as kamar")
            ->join('tbl_kamar', 'tbl_pembayaran.id_kamar=tbl_kamar.id')
            ->where('id_anggota', $row->id_anggota)
            ->where('DATE_FORMAT(tbl_pembayaran.jatuh_tempo, "%Y-%m")', $bulan_saat_ini)
            ->first();
        // jika tidak ada maka buatlah tagihan belum bayar
        if (!$cekTransaksiTahun) {
            $cekAnggota = $this->anggotaModel->find($row->id_anggota);
            // cek tanggal anggota mulai gabung kos2an (tanggal mulai ngekost)
            $tanggal_mulai = date("Y-m", strtotime($cekAnggota['tgl_kost']));

            // cek jenis sewa, untuk mencari bulan saat ini
            // foreach dari bulan mulai kerja sampai bulan saat ini
            $current_month = $tanggal_mulai;
            while ($current_month <= $bulan_saat_ini) {
                if ($current_month >= $bulan_saat_ini) {
                    break;
                }
                // penambahan berdasarkan jenis sewa
                if ($row->jenis_sewa == "3 bulan") {
                    // Tambahkan 3 bulan ke bulan saat ini
                    $current_month = date('Y-m', strtotime($current_month . $tiga_bulan));
                } else if ($row->jenis_sewa == "1 tahun") {
                    // Tambahkan 3 bulan ke bulan saat ini
                    $current_month = date('Y-m', strtotime($current_month . $setahun));
                } else {
                    $current_month = date('Y-m', strtotime($current_month . $perbulan));
                }
            }

            // jika tanggal mulai lebih besar dari bulan saat ini berarti belum mulai kost
            if ($tanggal_mulai > $bulan_saat_ini) {
                return "<p class='text-center'><span class='badge badge-info'>Tidak kos</span></p>";
            }

            if ($bulan_saat_ini != $current_month) {
                return "<p class='text-center'>-</p>";
            }

            // jika tanggal mulai kurang dari bulan saat ini berarti sudah mulai kost
            $jatuh_tempo = date("Y-m", strtotime($this->hitungJatuhTempo($cekAnggota['tgl_kost'], $bulan_saat_ini)));
            // if ($bulan == $jatuh_tempo) {
            if ($row->active == 1) {
                return
                    "<p class='text-center' style='font-size:12px'><a href='" . base_url("pembayaran/default/" . $this->hitungJatuhTempo($cekAnggota['tgl_kost'], $jatuh_tempo) . "/" . encryptID($cekAnggota['id'])) . "'class='btn btn-sm btn-danger'><i class='fa fa-edit'></i> <br> Belum <br> bayar</a>
                    <br>
                    <strong>Jatuh Tempo: </strong><br>" . date("d-m-Y", strtotime($jatuh_tempo)) . "
                    </p>";
            } else {
                // artinya tidak aktif
                return
                    "<p class='text-center'><span class='badge badge-danger'>Pengguna Tidak aktif</span></p>";
            }
            // }
        } else { // jika ada maka cek apakah sudah bayar, atau belum lunas
            $tanggal_masuk = date("Y-m-d", strtotime($cekTransaksiTahun['tanggal_mulai_sewa']));
            // ambil tanggal masuk untuk menentukan jatuh tempo
            $jatuh_tempo = date("Y-m", strtotime($this->hitungJatuhTempo($tanggal_masuk, $bulan_saat_ini)));
            if ($cekTransaksiTahun['status'] == 'lunas') {
                return "
                        <div style='font-size:12px;'>
                            <p><strong>No.Kamar:</strong><br> " . $cekTransaksiTahun['kamar'] . "<br>
                            <strong>Jatuh Tempo:</strong><br> " . date('d-m-Y', strtotime($cekTransaksiTahun['jatuh_tempo'])) . "<br>
                            <strong>Status:</strong><br> <span class='badge badge-success'>Lunas</span><br>
                            <strong>Total Bayar:</strong> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "<br>
                            <strong>Jenis Sewa:</strong> " . ucfirst($cekTransaksiTahun['tipe_pembayaran']) . "<br><br>
                            <a href='" . base_url("pembayaran/default/" . $this->hitungJatuhTempo($tanggal_masuk, $jatuh_tempo) . "/" . encryptID($row->id_anggota)) . "'>Lihat Detail >></a></p>
                        </div>
                        ";
                // jika belum lunas
            } else {
                if ($row->active == 1) {
                    return "
                            <p class='text-center' style='font-size:12px;'><a href='" . base_url("pembayaran/default/" . $cekTransaksiTahun['jatuh_tempo'] . "/" . encryptID($cekTransaksiTahun['id_anggota'])) . "' class='btn btn-sm btn-warning'><i class='fa fa-edit'></i><br> Belum <br>lunas</a><br>
                            <strong>Jatuh Tempo: </strong><br>" . date("d-m-Y", strtotime($jatuh_tempo)) . "
                            </p>
                        ";
                } else {
                    return "<p class='text-center' style='font-size:12px;'><span class='badge badge-warning'>Belum lunas</span><br>
                    <strong>Jatuh Tempo: </strong><br>" . date("d-m-Y", strtotime($jatuh_tempo)) . "
                    <br>
                    <br>
                    <a href='" . base_url("pembayaran/default/" . $this->hitungJatuhTempo($tanggal_masuk, $jatuh_tempo) . "/" . encryptID($row->id_anggota)) . "'>Lihat Detail >></a>
                    </p>";
                }
            }
        }
    }

    public function pembayaran_datatable($tahun)
    {
        $db = db_connect();
        $builder = $db->table('tbl_anggota')->select(
            "
            tbl_anggota.id as id_anggota,
            tbl_anggota.active,
            tbl_anggota.nama,tbl_anggota.id as id_a,tbl_anggota.telp,tbl_anggota.tgl_kost,tbl_anggota.jenis_sewa,
            tbl_kamar.nama as kamar
            "
        )
            // ->join('tbl_pembayaran', 'tbl_pembayaran.id_anggota=tbl_anggota.id', 'left')
            ->join('tbl_kamar', 'tbl_kamar.id=tbl_anggota.id_kamar');
        // ->join('tbl_kamar', 'tbl_kamar.id=tbl_pembayaran.id_kamar');
        // ->where('jenis_sewa', $jenis_sewa)
        // ->where('DATE_FORMAT(tbl_pembayaran.tanggal_mulai_sewa, "%Y")', $tahun);        // tanggal mulai sewa tahun ini

        return DataTable::of($builder)
            ->add('nama', function ($row) {
                return "<a href='" . base_url('anggota/edit/' . encryptID($row->id_anggota)) . "' target='_blank' title='Lihat detail anggota'>" . $row->nama . "</a> <br>
                <ul style='font-size:12px; padding-left:25px;'>
                    <li><strong>No.Kamar: </strong><br>" .  $row->kamar . "</li>
                    <li><strong>Tanggal Mulai: </strong><br>" .  date("d-m-Y", strtotime($row->tgl_kost)) . "</li>
                    <li><strong>Telp: </strong><br> <a href='https://wa.me/" . $row->telp . "' target='_blank'><i class='fab fa-whatsapp'></i> " . $row->telp . "</a></li>
                    <li><strong>Jenis Sewa: </strong><br>" .  ucfirst($row->jenis_sewa) . "</li>
                    <li><strong>Status Penyewa: </strong><br>" . ($row->active == 0 ? '<span class="badge badge-danger">Tidak aktif</span>' : '<span class="badge badge-success">Aktif</span>') . "</li>
                </ul>";
            })
            ->add('januari', function ($row) use ($tahun) {
                // cek pembayaran di bulan januari
                $bulan = date("Y-m", strtotime($tahun . '-1'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('februari', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-2'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('maret', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-3'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('april', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-4'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('mei', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-5'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('juni', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-6'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('juli', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-7'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('agustus', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-8'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('september', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-9'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('oktober', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-10'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('november', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-11'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('desember', function ($row) use ($tahun) {
                $bulan = date("Y-m", strtotime($tahun . '-12'));
                return $this->cekPembayaranPerBulan($row, $bulan);
            })
            ->add('total', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggota($row->id_anggota, $tahun);
                return $total_bayar;
            })
            ->add('total_bayar', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggota($row->id_anggota, $tahun);
                return "Rp. " .  number_format($total_bayar, 0, ",", ".");
            })
            ->addNumbering('no')
            ->filter(function ($builder, $request) {
                if ($request->status) {
                    if ($request->status != "all") {
                        $builder->where("tbl_anggota.active", $request->status);
                    }
                } else {
                    $builder->where("tbl_anggota.active !=", 1);
                }
            })
            ->toJson(true);
    }

    public function export()
    {
        $tahun = $this->request->getGet('tahun');
        $status = $this->request->getGet('status');
        if ($tahun == "" || $status == "") {
            session()->setFlashdata('msg', 'error#Pencarian data pembayaran tidak ditemukan!');
            return redirect()->to(base_url('riwayat_pembayaran'));
        }

        return view("riwayat_pembayaran/v_export_riwayat_pembayaran");
    }
}
