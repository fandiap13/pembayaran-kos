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
        // $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggotaTransfer(9, "2024");
        // dd($total_bayar);
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

    public function cekPembayaranPerBulan($row, $bulan)
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
                    <br><br>
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
                            <strong>Jenis Sewa:</strong> " . ucfirst($cekTransaksiTahun['tipe_pembayaran']) . "<br>
                            <strong>Tunai:</strong><br>Rp." . number_format($this->detailPembayaranModel->totalDibayarTunai($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                            <strong>Transfer:</strong><br>Rp." . number_format($this->detailPembayaranModel->totalDibayarTransfer($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                            <strong>Total Bayar:</strong> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "<br><br>
                            <a href='" . base_url("pembayaran/default/" . $this->hitungJatuhTempo($tanggal_masuk, $jatuh_tempo) . "/" . encryptID($row->id_anggota)) . "'>Lihat Detail >></a></p>
                        </div>
                        ";
                // jika belum lunas
            } else {
                if ($row->active == 1) {
                    return "
                            <p style='font-size:12px;'>
                            <a href='" . base_url("pembayaran/default/" . $cekTransaksiTahun['jatuh_tempo'] . "/" . encryptID($cekTransaksiTahun['id_anggota'])) . "' class='btn btn-sm btn-block btn-warning'><i class='fa fa-edit'></i><br> Belum <br>lunas</a>
                            <br>
                            <strong>No.Kamar:</strong><br> " . $cekTransaksiTahun['kamar'] . "<br>
                            <strong>Jatuh Tempo: </strong><br>" . date("d-m-Y", strtotime($jatuh_tempo)) . "<br>
                            <strong>Tunai: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayarTunai($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                            <strong>Transfer: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayarTransfer($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                            <strong>Total Bayar:</strong> <br> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "
                            <br>
                            <strong>Total Dibayar: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayar($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                            </p>
                        ";
                } else {
                    return "<p style='font-size:12px;'>
                    <strong>No.Kamar:</strong><br> " . $cekTransaksiTahun['kamar'] . "<br>
                    <strong>Jatuh Tempo: </strong><br>" . date("d-m-Y", strtotime($jatuh_tempo)) . "<br>
                    <strong>Status: </strong><br><span class='badge badge-warning'>Belum lunas</span><br>
                    <strong>Tunai: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayarTunai($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                    <strong>Transfer: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayarTransfer($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                    <strong>Total Bayar:</strong> <br> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "
                    <br>
                    <strong>Total Dibayar: </strong> <br> Rp." . number_format($this->detailPembayaranModel->totalDibayar($cekTransaksiTahun['id']), 0, ",", ".") . "<br>
                    <br>
                    <a href='" . base_url("pembayaran/default/" . $this->hitungJatuhTempo($tanggal_masuk, $jatuh_tempo) . "/" . encryptID($row->id_anggota)) . "'>Lihat Detail >></a>
                    </p>";
                }
            }
        }
    }

    public function cekPembayaranPerBulanNoStyle($row, $bulan)
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
                return "Tidak kos";
            }

            if ($bulan_saat_ini != $current_month) {
                return "Tidak ada tagihan";
            }

            // jika tanggal mulai kurang dari bulan saat ini berarti sudah mulai kost
            $jatuh_tempo = date("Y-m", strtotime($this->hitungJatuhTempo($cekAnggota['tgl_kost'], $bulan_saat_ini)));
            // if ($bulan == $jatuh_tempo) {
            if ($row->active == 1) {
                return
                    "
                    <ul>
                        <li><strong>Status: </strong>Belum bayar</li>
                        <li><strong>Jatuh Tempo: </strong>" . date("d-m-Y", strtotime($jatuh_tempo)) . "</li>
                    </ul>
                    ";
            } else {
                // artinya tidak aktif
                return
                    "Pengguna Tidak aktif";
            }
            // }
        } else { // jika ada maka cek apakah sudah bayar, atau belum lunas
            $tanggal_masuk = date("Y-m-d", strtotime($cekTransaksiTahun['tanggal_mulai_sewa']));
            // ambil tanggal masuk untuk menentukan jatuh tempo
            $jatuh_tempo = date("Y-m", strtotime($this->hitungJatuhTempo($tanggal_masuk, $bulan_saat_ini)));
            if ($cekTransaksiTahun['status'] == 'lunas') {
                return "
                        <ul>
                            <li><strong>No.Kamar:</strong> " . $cekTransaksiTahun['kamar'] . "</li>
                            <li><strong>Jatuh Tempo:</strong> " . date('d-m-Y', strtotime($cekTransaksiTahun['jatuh_tempo'])) . "</li>
                            <li><strong>Status:</strong> Lunas
                            <li><strong>Jenis Sewa:</strong>" . ucfirst($cekTransaksiTahun['tipe_pembayaran']) . "</li>
                            <li><strong>Tunai:</strong>Rp." . number_format($this->detailPembayaranModel->totalDibayarTunai($cekTransaksiTahun['id']), 0, ",", ".") . "</li>
                            <li><strong>Transfer:</strong>Rp." . number_format($this->detailPembayaranModel->totalDibayarTransfer($cekTransaksiTahun['id']), 0, ",", ".") . "</li>
                            <li><strong>Total Bayar:</strong> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "</li>
                        </ul>
                        ";
                // jika belum lunas
            } else {
                return "<ul>
                            <li><strong>No.Kamar:</strong> " . $cekTransaksiTahun['kamar'] . "</li>
                            <li><strong>Jatuh Tempo: </strong>" . date("d-m-Y", strtotime($jatuh_tempo)) . "</li>
                            <li><strong>Status: </strong><span class='badge badge-warning'>Belum lunas</span></li>
                            <li><strong>Tunai: </strong> Rp." . number_format($this->detailPembayaranModel->totalDibayarTunai($cekTransaksiTahun['id']), 0, ",", ".") . "</li>
                            <li><strong>Transfer: </strong> Rp." . number_format($this->detailPembayaranModel->totalDibayarTransfer($cekTransaksiTahun['id']), 0, ",", ".") . "</li>
                            <li><strong>Total Bayar:</strong> Rp." . number_format($cekTransaksiTahun['total_bayar'], 0, ",", ".") . "
                            </li>
                            <li><strong>Total Dibayar: </strong> Rp." . number_format($this->detailPembayaranModel->totalDibayar($cekTransaksiTahun['id']), 0, ",", ".") . "</li>
                        </ul>";
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
            tbl_kamar.nama as kamar,
            tbl_kamar.harga
            "
        )
            // ->join('tbl_pembayaran', 'tbl_pembayaran.id_anggota=tbl_anggota.id', 'left')
            ->join('tbl_kamar', 'tbl_kamar.id=tbl_anggota.id_kamar')
            ->orderBy('tbl_anggota.nama', 'asc');
        // ->join('tbl_kamar', 'tbl_kamar.id=tbl_pembayaran.id_kamar');
        // ->where('jenis_sewa', $jenis_sewa)
        // ->where('DATE_FORMAT(tbl_pembayaran.tanggal_mulai_sewa, "%Y")', $tahun);        // tanggal mulai sewa tahun ini

        return DataTable::of($builder)
            ->add('nama', function ($row) {
                return "<a href='" . base_url('anggota/edit/' . encryptID($row->id_anggota)) . "' target='_blank' title='Lihat detail anggota'>" . $row->nama . "</a> <br>
                <ul style='font-size:12px; padding-left:15px;'>
                    <li><strong>No.Kamar saat ini: </strong><br>" .  $row->kamar . "</li>
                    <li><strong>Sewa Perbulan: </strong><br>Rp " .  number_format($row->harga, 0, ",", ".") . "</li>
                    <li><strong>Tanggal Mulai: </strong><br>" .  date("d-m-Y", strtotime($row->tgl_kost)) . "</li>
                    <li><strong>Telp: </strong><br> <a href='https://wa.me/" . $row->telp . "' target='_blank'>+" . $row->telp . "</a></li>
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
            ->add('total_tunai', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggotaTunai($row->id_anggota, $tahun);
                return "Rp. " .  number_format($total_bayar, 0, ",", ".");
            })
            ->add('tunai', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggotaTunai($row->id_anggota, $tahun);
                return $total_bayar;
            })
            ->add('total_transfer', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggotaTransfer($row->id_anggota, $tahun);
                return "Rp. " .  number_format($total_bayar, 0, ",", ".");
            })
            ->add('transfer', function ($row) use ($tahun) {
                $total_bayar = $this->detailPembayaranModel->totalDibayarByIDAnggotaTransfer($row->id_anggota, $tahun);
                return $total_bayar;
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

        $db = db_connect();
        $riwayat = $db->table('tbl_anggota')->select(
            "
            tbl_anggota.id as id_anggota,
            tbl_anggota.active,
            tbl_anggota.nama,tbl_anggota.id as id_a,tbl_anggota.telp,tbl_anggota.tgl_kost,tbl_anggota.jenis_sewa,
            tbl_kamar.nama as kamar,
            tbl_kamar.harga
            "
        )->join('tbl_kamar', 'tbl_kamar.id=tbl_anggota.id_kamar')->orderBy('tbl_anggota.nama', 'asc');

        $status_anggota = "";
        if ($status != 'all') {
            $riwayat->where("tbl_anggota.active", $status == 'all' ? "" : $status);
            $status_anggota = $status == "0" ? "Tidak Aktif " : "Aktif ";
        }

        $result = $riwayat->get()->getResult();

        return view("riwayat_pembayaran/v_export_riwayat_pembayaran", [
            'title' => "Riwayat Pembayaran Anggota Kos " . $status_anggota . "Tahun " . $tahun,
            'riwayat' => $result,
            'tahun' => $tahun,
            'status' => $status,
        ]);
    }
}
