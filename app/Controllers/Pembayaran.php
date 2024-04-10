<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\DetailPembayaranModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use DateTime;
use Hermawan\DataTables\DataTable;

class Pembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $detailPembayaranModel;
    protected $anggotaModel;
    protected $kamarModel;
    protected $idAdmin;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->detailPembayaranModel = new DetailPembayaranModel();
        $this->anggotaModel = new AnggotaModel();
        $this->kamarModel = new KamarModel();
        $this->idAdmin = decryptID(session("LoggedUserData")['id_admin']);
    }

    public function index()
    {
        return view('pembayaran/v_index', [
            'title' => "Transaksi Pembayaran Kos",
        ]);
    }

    function hitungJatuhTempo($tgl_kost, $current_month)
    {
        $arr_mulai = explode("-", $tgl_kost);
        $arr_today = explode("-", $current_month);
        $jatuh_tempo = date("Y-m-d", strtotime($arr_today[0] . "-" . $arr_today[1] . "-" . $arr_mulai[2]));
        return $jatuh_tempo;
    }

    function cekJatuhTempo($current_month, $tenggat_waktu, $row, $jenis_sewa = null)
    {
        // $today = "2024-03-29";
        $tgl_hari_ini = date("Y-m-d");
        $tanggal_hari_ini = new DateTime($tgl_hari_ini);
        $jatuh_tempo = $this->hitungJatuhTempo($row->tgl_kost, $current_month);
        // $tanggal_pembayaran = $row->tanggal;

        // pemilihan jenis sewa
        if ($jenis_sewa == '3 bulan') {
            $pengurangan = "-3 month";
            $penambahan = "+3 month";
        } else if ($jenis_sewa == '1 tahun') {
            $pengurangan = "-1 year";
            $penambahan = "+1 year";
        } else {
            $pengurangan = "-1 month";
            $penambahan = "+1 month";
        }

        $text_bulan_lalu = "";
        $text_bulan_ini = "";
        $text_bulan_depan = "";

        // TODO: Mengecek pembayaran bulan lalu (jika ada)
        // kita mencari jatuh tempo untuk bulan lalu, dan dikurangi 1 bulan berikutnya
        $bulan_lalu = date("Y-m", strtotime($pengurangan, strtotime($current_month)));
        if ($bulan_lalu >= date("Y-m", strtotime($row->tgl_kost))) {
            // cek pembayaran bulan lalu
            $pBulanLalu = $this->pembayaranModel->where('id_anggota', $row->id_a)->where('DATE_FORMAT(jatuh_tempo, "%Y-%m")', $bulan_lalu)->first();
            // menghitung jatuh tempo bulan depan
            $jatuh_tempo_bulan_lalu = $this->hitungJatuhTempo($jatuh_tempo, $bulan_lalu);
            if (!$pBulanLalu) {
                // Konversi kedua tanggal ke objek DateTime
                $tgl_jatuh_tempo_bulan_lalu = new DateTime($jatuh_tempo_bulan_lalu);
                // Hitung selisih hari antara tanggal jatuh tempo dan tanggal saat ini
                $selisih_bulan_lalu = $tgl_jatuh_tempo_bulan_lalu->diff($tanggal_hari_ini)->days;
                if ($selisih_bulan_lalu == 0) {
                    $text_bulan_lalu = "<li>Jatuh tempo bulan lalu " . date("d F Y", strtotime($jatuh_tempo_bulan_lalu)) . " <br> <span class='badge badge-danger'>Tagihan bulan kemarin, " . date("F Y", strtotime($jatuh_tempo_bulan_lalu)) . " harus bayar hari ini</span></li>";
                } else {
                    $text_bulan_lalu = "<li>Jatuh tempo bulan lalu " . date("d F Y", strtotime($jatuh_tempo_bulan_lalu)) . " <br> <span class='badge badge-danger'>Sudah " . $selisih_bulan_lalu . " hari belum bayar tagihan bulan " . date("F Y", strtotime($jatuh_tempo_bulan_lalu)) . " </span></li>";
                }
            } else {
                $text_bulan_ini = "<li>Jatuh tempo bulan sebelumnya " . date("d F Y", strtotime($jatuh_tempo_bulan_lalu)) . " sudah <span class='badge badge-success'>lunas</span></li>";
            }
        }
        // TODO: Mengecek pembayaran bulan depan (jika ada)
        // kita mencari jatuh tempo untuk bulan depan, dan ditambahkan 1 bulan berikutnya
        $bulan_depan = date("Y-m", strtotime($penambahan, strtotime($current_month)));
        if ($bulan_depan >= date("Y-m", strtotime($row->tgl_kost))) {
            // cek pembayaran bulan depan
            $pBulandepan = $this->pembayaranModel->where('id_anggota', $row->id_a)->where('DATE_FORMAT(jatuh_tempo, "%Y-%m")', $bulan_depan)->first();
            // menghitung jatuh tempo bulan depan
            $jatuh_tempo_bulan_depan = $this->hitungJatuhTempo($jatuh_tempo, $bulan_depan);
            if (!$pBulandepan) {
                // Konversi kedua tanggal ke objek DateTime
                $tgl_jatuh_tempo_bulan_depan = new DateTime($jatuh_tempo_bulan_depan);
                // Hitung selisih hari antara tanggal jatuh tempo dan tanggal saat ini
                $selisih_bulan_depan = $tgl_jatuh_tempo_bulan_depan->diff($tanggal_hari_ini)->days;
                if ($selisih_bulan_depan == 0) {
                    $text_bulan_depan = "<li>Jatuh tempo bulan depan " . date("d F Y", strtotime($jatuh_tempo_bulan_depan)) . " <br> <span class='badge badge-danger'>Tagihan bulan kemarin, " . date("F Y", strtotime($jatuh_tempo_bulan_depan)) . " harus bayar hari ini</span></li>";
                } else {
                    $text_bulan_depan = "<li>Jatuh tempo bulan depan " . date("d F Y", strtotime($jatuh_tempo_bulan_depan)) . " <br> <span class='badge badge-" . (($selisih_bulan_depan <= $tenggat_waktu) ? 'danger' : 'info') . "'>" . $selisih_bulan_depan . " hari lagi akan bayar tagihan bulan " . date("F Y", strtotime($jatuh_tempo_bulan_depan)) . " </span></li>";
                }
            } else {
                $text_bulan_ini = "<li>Jatuh tempo bulan depan " . date("d F Y", strtotime($jatuh_tempo_bulan_depan)) . " sudah <span class='badge badge-success'>lunas</span></li>";;
            }
        }
        // TODO: Mengecek jatuh tempo BULAN INI (jika ada)
        $bulan_ini = date("Y-m", strtotime($current_month));
        // cek pembayaran bulan depan
        $pBulanIni = $this->pembayaranModel->where('id_anggota', $row->id_a)->where('DATE_FORMAT(jatuh_tempo, "%Y-%m")', $bulan_ini)->first();
        // menghitung jatuh tempo bulan ini
        $jatuh_tempo_bulan_ini = $this->hitungJatuhTempo($jatuh_tempo, $bulan_ini);
        if (!$pBulanIni) {
            // Konversi kedua tanggal ke objek DateTime
            $tgl_jatuh_tempo_bulan_ini = new DateTime($jatuh_tempo_bulan_ini);
            // Hitung selisih hari antara tanggal jatuh tempo dan tanggal saat ini
            $selisih_bulan_ini = $tgl_jatuh_tempo_bulan_ini->diff($tanggal_hari_ini)->days;
            if ($tanggal_hari_ini <= $tgl_jatuh_tempo_bulan_ini) {
                if ($selisih_bulan_ini == 0) {
                    $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . " <br> <span class='badge badge-danger'>Tagihan bulan ini, harus dibayar hari ini</span></li>";
                } else {
                    $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . " <br> <span class='badge badge-" . (($selisih_bulan_ini <= $tenggat_waktu) ? 'danger' : 'info') . "'>" . $selisih_bulan_ini . " hari lagi akan bayar</span></li>";
                }
            } else {
                $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . " <br><span class='badge badge-danger'>Telat bayar " . $selisih_bulan_ini . " hari</span></li>";
            }
        } else {
            if ($pBulanIni['status'] == "lunas") {
                $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . " sudah <span class='badge badge-success'>Lunas</span></li>";
            } else if ($pBulanIni['status'] == "cicil") {
                $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . " masih <span class='badge badge-warning'>Dicicil</span></li>";
            } else {
                $text_bulan_ini = "<li>Jatuh tempo bulan ini " . date("d F Y", strtotime($jatuh_tempo_bulan_ini)) . "  <span class='badge badge-danger'>Belum diproses</span></li>";
            }
        }

        return
            "
                <p class='text-left'>
                    <ul>" . $text_bulan_ini . $text_bulan_lalu . $text_bulan_depan . "</ul>
                </p>
            ";
    }

    public function pembayaran_datatable($bulan, $jenis_sewa)
    {
        $tiga_bulan = ' +3 months';
        $setahun = ' +1 year';
        $tenggat_waktu = 7;   // 7 hari
        $db = db_connect();
        $builder = $db->table('tbl_anggota')->select(
            "
            tbl_anggota.nama,tbl_anggota.id as id_a,tbl_anggota.telp,tbl_anggota.tgl_kost,tbl_anggota.jenis_sewa,
            tbl_kamar.nama as kamar,tbl_pembayaran.*
            "
        )
            ->join('tbl_kamar', 'tbl_kamar.id=tbl_anggota.id_kamar');
        if ($jenis_sewa == "1 tahun") {
            $builder
                ->join('tbl_pembayaran', 'tbl_pembayaran.id_anggota=tbl_anggota.id AND DATE_FORMAT(tbl_pembayaran.jatuh_tempo, "%Y") = ' . $db->escape(date("Y", strtotime($bulan))), 'left')
                ->where('jenis_sewa', $jenis_sewa)
                ->where('DATE_FORMAT(tbl_anggota.tgl_kost, "%Y") <=', date("Y", strtotime($bulan))); // mencari yang sudah lunas bulan ini
        } else if ($jenis_sewa == "3 bulan") {
            $builder
                ->join('tbl_pembayaran', 'tbl_pembayaran.id_anggota=tbl_anggota.id AND DATE_FORMAT(tbl_pembayaran.jatuh_tempo, "%Y-%m") = ' . $db->escape(date("Y-m", strtotime($bulan))), 'left')
                ->where('jenis_sewa', $jenis_sewa)
                ->where('DATE_FORMAT(tbl_anggota.tgl_kost, "%Y-%m") <=', date("Y-m", strtotime($bulan))); // mencari yang sudah lunas bulan ini
        } else {
            $builder
                ->join('tbl_pembayaran', 'tbl_pembayaran.id_anggota=tbl_anggota.id AND DATE_FORMAT(tbl_pembayaran.jatuh_tempo, "%Y-%m") = ' . $db->escape(date("Y-m", strtotime($bulan))), 'left')
                ->where('jenis_sewa', $jenis_sewa)
                ->where('DATE_FORMAT(tbl_anggota.tgl_kost, "%Y-%m") <=', date("Y-m", strtotime($bulan)));   // mencari yang lunas bulan ini
        }
        // filter anggota aktif
        $builder->where('tbl_anggota.active', 1)->orderBy('nama', 'asc');

        return DataTable::of($builder)
            ->add('kamar', function ($row) {
                if ($row->id_kamar != null) {
                    $cekKamarTransaksi = $this->kamarModel->find($row->id_kamar);
                    if ($cekKamarTransaksi && ($cekKamarTransaksi['id'] != $row->id_kamar)) {
                        // kamar transaksi beda sendiri
                        // return "kamar transaksi beda sendiri";
                        return $cekKamarTransaksi['nama'];
                    } else {
                        return $row->kamar;
                    }
                }
                return $row->kamar;
            })
            ->add('nama', function ($row) {
                return $row->nama;
            })
            ->add('tgl_kost', function ($row) {
                return "<p class='text-center'>" . date("d F Y", strtotime($row->tgl_kost)) . "</p>";
            })
            ->add('telp', function ($row) {
                return "<a href='https://wa.me/" . $row->telp . "' target='_blank'><i class='fab fa-whatsapp'></i> " . $row->telp . "</a>";
            })
            ->add('tgl_pembayaran', function ($row) {
                if ($row->tanggal != "" && $row->tanggal != null) {
                    $tgl_bayar = date("d F Y", strtotime($row->tanggal));
                } else {
                    $tgl_bayar = "-";
                }
                return "<p class='text-center'>" . $tgl_bayar . '</p>';
            })
            ->add('jatuh_tempo', function ($row) use ($bulan, $jenis_sewa, $tiga_bulan, $setahun, $tenggat_waktu) {
                $text_jatuh_tempo = "";
                if ($jenis_sewa == '3 bulan') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 3 bulan ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $tiga_bulan));
                    }

                    if ($current_month == $bulan_ini) {
                        $text_jatuh_tempo = $this->cekJatuhTempo($current_month, $tenggat_waktu, $row, '3 bulan');
                    } else {
                        $text_jatuh_tempo = "<span class='badge badge-info'><i class='fa fa-info-circle mr-2'></i>Tidak ada tagihan di bulan " . date("F Y", strtotime($current_month)) . "</span>";
                    }
                } else if ($jenis_sewa == '1 tahun') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 1 tahun ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $setahun));
                    }
                    if ($current_month == $bulan_ini) {
                        $text_jatuh_tempo = $this->cekJatuhTempo($current_month, $tenggat_waktu, $row, '1 tahun');
                    } else {
                        $text_jatuh_tempo = "<span class='badge badge-info'><i class='fa fa-info-circle mr-2'></i>Tidak ada tagihan di bulan " . date("F Y", strtotime($current_month)) . "</span>";
                    }
                } else {
                    $text_jatuh_tempo = $this->cekJatuhTempo($bulan, $tenggat_waktu, $row);
                }

                return $text_jatuh_tempo;
            })
            ->add('status', function ($row) use ($bulan, $jenis_sewa, $tiga_bulan, $setahun) {
                $show_status = false;
                if ($jenis_sewa == '3 bulan') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 3 bulan ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $tiga_bulan));
                    }

                    if ($current_month == $bulan_ini) {
                        $show_status = true;
                    }
                } else if ($jenis_sewa == '1 tahun') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 1 tahun ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $setahun));
                    }
                    if ($current_month == $bulan_ini) {
                        $show_status = true;
                    }
                } else {
                    $show_status = true;
                }

                if ($show_status) {
                    if ($row->status == 'lunas') {
                        $status = "<span class='badge badge-success'>Lunas</span";
                    } else if ($row->status == 'cicil') {
                        $status = "<span class='badge badge-warning'>Cicil</span";
                    } else if ($row->status == 'proses') {
                        $status = "<span class='badge badge-danger'>Proses belum selesai</span";
                    } else {
                        $status = "<span class='badge badge-danger'>Belum Bayar</span";
                    }
                } else {
                    $status = "<span class='badge badge-info'><i class='fa fa-info-circle mr-2'></i>Tidak ada tagihan di bulan " . date("F Y", strtotime($current_month)) . "</span>";
                }

                return $status;
            })
            ->add('action', function ($row) use ($bulan, $jenis_sewa, $setahun, $tiga_bulan) {
                $show_status = false;
                if ($jenis_sewa == '3 bulan') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    // bulan ini, lebih tepatnya bulan dari input
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 3 bulan ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $tiga_bulan));
                    }

                    if ($current_month == $bulan_ini) {
                        $show_status = true;
                    }
                } else if ($jenis_sewa == '1 tahun') {
                    // foreach dari bulan mulai kerja sampai bulan saat ini
                    $bulan_mulai_kost = date('Y-m', strtotime($row->tgl_kost));
                    $bulan_ini = $bulan;
                    $current_month = $bulan_mulai_kost;
                    while ($current_month <= $bulan_ini) {
                        if ($current_month >= $bulan_ini) {
                            break;
                        }
                        // Tambahkan 1 tahun ke bulan saat ini
                        $current_month = date('Y-m', strtotime($current_month . $setahun));
                    }
                    if ($current_month == $bulan_ini) {
                        $show_status = true;
                    }
                } else {
                    $show_status = true;
                }

                if ($show_status) {
                    // $arr_mulai = explode("-", $row->tgl_kost);
                    // $arr_today = explode("-", $bulan);
                    // $jatuh_tempo = date("Y-m-d", strtotime($arr_today[0] . "-" . $arr_today[1] . "-" . $arr_mulai[2]));
                    $jatuh_tempo = $this->hitungJatuhTempo($row->tgl_kost, $bulan);

                    $aksi = "<a href='" . base_url('pembayaran/default/' . $jatuh_tempo . '/' . encryptID($row->id_a)) . "' class='btn btn-info btn-sm' title='detail'><i class='fa fa-edit'></i> Pembayaran</a>";

                    return "
                    <div class='text-center'>
                        " . $aksi . "
                    </div>
                    ";
                } else {
                    return "-";
                }
            })
            ->addNumbering('no')
            ->filter(function ($builder, $request) {
                if ($request->status) {
                    if ($request->status == "belum bayar") {
                        $status = null;
                    } else {
                        $status = $request->status;
                    }
                    $builder->where("status", $status);
                }
            })
            ->toJson(true);
    }

    public function pembayaran($jatuh_tempo, $id)
    {
        // try {
        $id_anggota = decryptID($id);
        // cari data pembayaran anggota berdasarkan tanggal
        $cek_pembayaran = $this->pembayaranModel
            ->select(
                "
                    tbl_pembayaran.*,
                    tbl_anggota.nama as nama_anggota, tbl_anggota.telp, tbl_anggota.biaya_tambahan,
                    tbl_kamar.harga,tbl_kamar.nama as kamar,
                    tbl_admin.nama as admin,
                    "
            )
            ->join('tbl_anggota', 'tbl_anggota.id=tbl_pembayaran.id_anggota')
            ->join('tbl_kamar', 'tbl_kamar.id=tbl_pembayaran.id_kamar') // mengambil id kamar dari pembayaran 
            ->join('tbl_admin', 'tbl_admin.id=tbl_pembayaran.id_admin')
            // ->where('tbl_anggota.active', 1)  // matikan ini agar semua data ditampilkan bahkan yang belum aktif
            ->where('tbl_pembayaran.id_anggota', $id_anggota)
            ->where('DATE_FORMAT(tbl_pembayaran.jatuh_tempo, "%Y-%m")', date("Y-m", strtotime($jatuh_tempo)))->first();

        // dd($tanggal);
        if ($cek_pembayaran) {
            // cek dibayar
            $total_dibayar = $this->detailPembayaranModel->totalDibayar($cek_pembayaran['id']);
            return view('pembayaran/v_edit_pembayaran', [
                'title' => "Edit Pembayaran Kos " . date("F Y", strtotime($jatuh_tempo)),
                'tanggal' => date("Y-m-d"),
                'jatuh_tempo' => $jatuh_tempo,
                'pembayaran' => $cek_pembayaran,
                'detail_pembayaran' => $this->detailPembayaranModel
                    ->select("tbl_detail_pembayaran.*,tbl_admin.nama as admin")
                    ->join('tbl_admin', 'tbl_admin.id=tbl_detail_pembayaran.id_admin', 'left')
                    ->where('id_pembayaran', $cek_pembayaran['id'])->orderBy('tanggal', 'DESC')->findAll(),
                'total_dibayar' => $total_dibayar,
            ]);
        } else {
            // dd($this->anggotaModel->getAllAnggota($id_anggota)->first());
            // ambil data anggota 
            $anggota =  $this->anggotaModel->getAllAnggota($id_anggota)->first();
            if (!$anggota) {
                session()->setFlashdata('msg', "error#Terdapat kesalahan pada sistem!");
                return redirect()->to(base_url('pembayaran'));
            }

            // perhitungan total bayar
            if ($anggota['jenis_sewa'] == '1 tahun') {
                $total_bayar = intval($anggota['harga'] * 12);
                $totalBiayaTambahan = intval($anggota['biaya_tambahan']);
            } else if ($anggota['jenis_sewa'] == '3 bulan') {
                $total_bayar = intval($anggota['harga'] * 3);
                $totalBiayaTambahan = intval($anggota['biaya_tambahan']);
            } else {
                $total_bayar = intval($anggota['harga']);
                $totalBiayaTambahan = intval($anggota['biaya_tambahan']);
            }
            // dd($anggota);
            return view('pembayaran/v_pembayaran', [
                'title' => "Tambah Pembayaran Kos Pada " . date("F Y", strtotime($jatuh_tempo)),
                'tanggal' => date("Y-m-d"),
                'jatuh_tempo' => $jatuh_tempo,
                'totalBayar' => $total_bayar,
                'totalBiayaTambahan' => $totalBiayaTambahan,
                'anggota' => $anggota,
            ]);
        }
        // } catch (\Throwable $th) {
        //     session()->setFlashdata('msg', 'error#Terdapat kesalahan pada sistem !');
        //     return redirect()->to(base_url('pembayaran'));
        // }
    }

    public function tambah_pembayaran()
    {
        try {
            $data = trimAllPostInput($this->request->getPost());
            $cek_pembayaran = $this->pembayaranModel->where('id_anggota', $data['id_anggota'])
                ->where('DATE_FORMAT(jatuh_tempo, "%Y-%m")', date("Y-m", strtotime($data['jatuh_tempo'])))->first();
            if ($cek_pembayaran) {
                echo json_encode([
                    'error' => true,
                    'message' => "Pembayaran sudah tercatat!"
                ]);
                return;
            }

            $data['status'] = 'proses';
            $data['id_admin'] = $this->idAdmin;
            $data['tanggal'] = $data['tanggal'] . " " . date("H:i:s");
            $data['no_pembayaran'] = $this->pembayaranModel->getNoPembayaran($data['id_kamar']);
            // echo json_encode($data);
            // return;

            $this->pembayaranModel->insert($data);
            echo json_encode([
                'success' => true,
                'message' => "Pembayaran berhasil tersimpan!"
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'error' => true,
                'message' => "Terdapat kesalahan pada sistem!"
            ]);
        }
    }

    public function tambah_pelunasan()
    {
        if ($this->request->isAJAX()) {
            try {
                $id_pembayaran = $this->request->getPost('id_pembayaran');
                $cek_pembayaran = $this->pembayaranModel->find($id_pembayaran);
                if (!$cek_pembayaran) {
                    echo json_encode([
                        'error' => true,
                        'message' => "Data pembayaran tidak ditemukan!"
                    ]);
                    return;
                }

                if ($cek_pembayaran['status'] == "lunas") {
                    echo json_encode([
                        'info' => true,
                        'message' => "Pembayaran anda sudah lunas"
                    ]);
                    return;
                }

                $total_bayar = $cek_pembayaran['total_bayar'];
                $total_dibayar = $this->detailPembayaranModel->totalDibayar($cek_pembayaran['id']);
                $sisa_bayar = intval($total_bayar - $total_dibayar);
                $bayar = intval($this->request->getPost('bayar'));
                if ($sisa_bayar < $bayar) {
                    echo json_encode([
                        'error' => true,
                        'message' => "Uang anda terlalu banyak! Sisa pembayaran adalah Rp " . number_format($sisa_bayar, 0, ",", ".")
                    ]);
                    return;
                } else if ($sisa_bayar == $bayar) {
                    $status = "lunas";
                } else {
                    $status = "cicil";
                }

                // update status
                $this->pembayaranModel->update($id_pembayaran, [
                    'status' => $status,
                ]);
                // dd($status);
                $this->detailPembayaranModel->insert([
                    'id_pembayaran' => $id_pembayaran,
                    'tanggal' => date("Y-m-d H:i:s"),
                    'id_admin' =>  $this->idAdmin,
                    'id_anggota' =>  $cek_pembayaran['id_anggota'],
                    'bayar' => $bayar,
                ]);

                echo json_encode([
                    'success' => true,
                    'message' => "Pembayaran berhasil tersimpan"
                ]);
            } catch (\Throwable $th) {
                echo json_encode([
                    'error' => true,
                    'message' => "Terdapat kesalahan pada sistem!"
                ]);
            }
        }
    }

    public function hapus_detail_pembayaran($id)
    {
        $id = decryptID($id);
        $this->detailPembayaranModel->delete($id);
        // ubah status
        $id_pembayaran = $this->request->getGet('id_pembayaran');
        $total_dibayar = $this->detailPembayaranModel->totalDibayar($id_pembayaran);
        $pembayaran = $this->pembayaranModel->where('id', $id_pembayaran)->first();

        // echo json_encode($total_dibayar);
        // return;

        if (intval($total_dibayar) == 0) {
            $status = "proses";
        } else if (intval($pembayaran['total_bayar']) > intval($total_dibayar)) {
            $status = "cicil";
        } else {
            $status = "lunas";
        }
        $this->pembayaranModel->update($id_pembayaran, [
            'status' => $status
        ]);

        echo json_encode([
            'success' => true,
            'message' => "Detail Pembayaran berhasil dihapus"
        ]);
    }

    public function hapus_transaksi_pembayaran($id)
    {
        $id = decryptID($id);

        $this->detailPembayaranModel->where('id_pembayaran', $id)->delete();
        $this->pembayaranModel->delete($id);

        echo json_encode([
            'success' => true,
            'message' => "Transaksi Pembayaran berhasil dihapus"
        ]);
    }

    public function cetak_kuitansi($id)
    {
        try {
            $cekPembayaran = $this->pembayaranModel
                ->select('tbl_pembayaran.*,a.nama as admin,an.nama as nama')
                ->join("tbl_admin as a", "a.id=tbl_pembayaran.id_admin")
                ->join("tbl_anggota as an", "an.id=tbl_pembayaran.id_anggota")
                ->find(decryptID($id));
            return view('pembayaran/v_cetak_kuitansi_pembayaran', [
                'title' => "Cetak Kuitansi " . $cekPembayaran['no_pembayaran'],
                'pembayaran' => $cekPembayaran
            ]);
        } catch (\Throwable $th) {
            // throw $th;
            echo "Data pembayaran tidak ditemukan!";
        }
    }
}
