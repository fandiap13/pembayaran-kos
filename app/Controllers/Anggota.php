<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnggotaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use Hermawan\DataTables\DataTable;

class Anggota extends BaseController
{
    protected $kamarModel;
    protected $anggotaModel;

    public function __construct()
    {
        $this->kamarModel = new KamarModel();
        $this->anggotaModel = new AnggotaModel();
    }

    public function index()
    {
        return view('anggota/v_index', [
            'title' => "Anggota Aktif Kos",
        ]);
    }

    public function anggota_tidak_aktif()
    {
        return view('anggota_tidak_aktif/v_index', [
            'title' => "Anggota Kos Tidak Aktif",
        ]);
    }

    public function anggota_datatable()
    {

        $builder = $this->anggotaModel->getAllAnggota();
        return DataTable::of($builder)
            ->add('tgl_kost', function ($row) {
                return "<p class='text-center'>" . date('d F Y', strtotime($row->tgl_kost)) . "</p>";
            })
            ->add('kamar', function ($row) {
                return $row->kamar;
            })
            ->add('nama', function ($row) {
                return $row->nama;
            })
            ->add('jenis_sewa', function ($row) {
                return ucfirst($row->jenis_sewa);
            })
            ->add('lantai', function ($row) {
                return ucfirst($row->lantai);
            })
            ->add('telp', function ($row) {
                return "<a href='https://wa.me/" . $row->telp . "' target='_blank'><i class='fab fa-whatsapp'></i> " . $row->telp . "</a>";
            })
            ->add('harga', function ($row) {
                return "<p class='text-right'>Rp " . number_format($row->harga, 0, ",", ".") . "</p>";
            })
            ->add('action', function ($row) {
                $aksi = " <a href='" . base_url("anggota/edit/" . encryptID($row->id)) . "' class='btn btn-primary btn-sm' title='edit'><i class='fa fa-edit'></i></a>";
                // $pembayaranModel = new PembayaranModel();
                // $cekForeign = $pembayaranModel->where("id_anggota", $row->id)->first();
                // if (!$cekForeign) {
                $aksi .= " <button type='button' onclick='hapus(\"" . encryptID($row->id) . "\")' class='btn btn-danger btn-sm' title='hapus'><i class='fas fa-user-slash'></i></button>";
                // }

                return "
                <div class='text-center'>
                    " . $aksi . "
                </div>
                ";
            })
            ->addNumbering('no')
            ->filter(function ($builder, $request) {
                if ($request->lantai) {
                    $builder->where('tbl_kamar.lantai', $request->lantai);
                }
                if ($request->tanggal_awal && $request->tanggal_selesai) {
                    $builder->where("DATE_FORMAT(tgl_kost, '%Y-%m-%d') BETWEEN '" . $request->tanggal_awal . "' and '" . $request->tanggal_selesai . "'");
                }
                if ($request->tanggal_awal && $request->tanggal_selesai && $request->lantai) {
                    $builder
                        ->where('tbl_kamar.lantai', $request->lantai)
                        ->where("DATE_FORMAT(tgl_kost, '%Y-%m-%d') BETWEEN '" . $request->tanggal_awal . "' and '" . $request->tanggal_selesai . "'");
                }
            })
            ->toJson(true);
    }

    public function anggota_tidak_aktif_datatable()
    {

        $builder = $this->anggotaModel->getAllAnggotaTidakAktif();
        return DataTable::of($builder)
            ->add('tgl_kost', function ($row) {
                return "<p class='text-center'>" . date('d F Y', strtotime($row->tgl_kost)) . "</p>";
            })
            ->add('kamar', function ($row) {
                return $row->kamar;
            })
            ->add('nama', function ($row) {
                return $row->nama;
            })
            ->add('jenis_sewa', function ($row) {
                return ucfirst($row->jenis_sewa);
            })
            ->add('lantai', function ($row) {
                return ucfirst($row->lantai);
            })
            ->add('telp', function ($row) {
                return "<a href='https://wa.me/" . $row->telp . "' target='_blank'><i class='fab fa-whatsapp'></i> " . $row->telp . "</a>";
            })
            ->add('harga', function ($row) {
                return "<p class='text-right'>Rp " . number_format($row->harga, 0, ",", ".") . "</p>";
            })
            ->add('action', function ($row) {
                $aksi = " <button onclick='restoreData(\"" . encryptID($row->id) . "\")' class='btn btn-primary btn-sm mb-2' title='restore'>
                <i class='fas fa-sync-alt'></i> Restore data</button>";
                // $pembayaranModel = new PembayaranModel();
                // $cekForeign = $pembayaranModel->where("id_anggota", $row->id)->first();
                // if (!$cekForeign) {
                // $aksi .= "<button type='button' onclick='hapusPermanen(\"" . encryptID($row->id) . "\")' class='btn btn-danger btn-sm mb-2' title='hapus permanen'><i class='fa fa-trash-alt'></i> Hapus Permanen</button>";
                // }

                return "
                <div class='text-center'>
                    " . $aksi . "
                </div>
                ";
            })
            ->addNumbering('no')
            ->toJson(true);
    }

    public function tambah()
    {
        return view('anggota/v_tambah', [
            'title' => "Tambah Anggota Kos",
            'kamar' => $this->kamarModel->getKamarTersedia(),
        ]);
    }

    public function edit($id)
    {
        try {
            $id = decryptID($id);
            $cekAnggota = $this->anggotaModel->find($id);
            if (!$cekAnggota) {
                session()->setFlashdata('msg', "error#Anggota tidak ditemukan!");
                redirect()->to(base_url('anggota'));
            }

            // dd($this->kamarModel->getKamarTersedia($id));

            return view('anggota/v_edit', [
                'title' => "Edit Anggota Kos",
                'kamar' => $this->kamarModel->getKamarTersedia($id),
                'data' => $cekAnggota
            ]);
        } catch (\Throwable $th) {
            session()->setFlashdata('msg', "error#Anggota tidak ditemukan!");
            redirect()->to(base_url('anggota'));
        }
    }

    public function detail($id)
    {
        try {
            $detailAnggota = $this->anggotaModel->find($id);
            if (!$detailAnggota) {
                echo json_encode([
                    'error' => true,
                    'message' => "Anggota kos tidak ditemukan!"
                ]);
            }
            echo json_encode([
                'success' => true,
                'data' => $detailAnggota,
                'message' => "Anggota kos berhasil ditampilkan"
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'error' => true,
                'message' => "Terdapat kesalahan pada sistem!"
            ]);
        }
    }

    public function create()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'nama' => [
                    'label' => 'Nama',
                    // 'rules' => 'required|max_length[150]|is_unique[tbl_anggota.nama]',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                        'is_unique' => '{field} sudah ada !',
                    ]
                ],
                'tgl_kost' => [
                    'label' => 'Tanggal Mulai Kos',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'jenis_sewa' => [
                    'label' => 'Jenis Sewa',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'telp' => [
                    'label' => 'No telp/WA',
                    'rules' => 'required|max_length[15]|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 14 karakter !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'telp_kerabat' => [
                    'label' => 'No telp/WA (orang tua/kerabat)',
                    'rules' => 'required|max_length[15]|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 14 karakter !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'alamat' => [
                    'label' => 'Alamat',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'id_kamar' => [
                    'label' => 'Kamar Kos',
                    // 'rules' => 'required|is_unique[tbl_anggota.id_kamar]',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'is_unique' => "{field} sudah diisi/digunakan !"
                    ]
                ],
                'biaya_tambahan' => [
                    'label' => 'Biaya Tambahan',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
            ]);

            if (!$valid) {
                exit(json_encode([
                    'error' => true,
                    'message' => "Validation Failed",
                    'errors' => $validation->getErrors()
                ]));
            }

            $data = trimAllPostInput($this->request->getPost());
            $data['nama'] = strtoupper($data['nama']);
            $data['active'] = 1; // mengaktifkan akun anggota
            $save = $this->anggotaModel->insert($data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Anggota kos " . $data['nama'] . " berhasil disimpan"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Anggota kos " . $data['nama'] . " gagal disimpan!"
                ]);
            }
        }
    }

    public function update($id_anggota)
    {
        if ($this->request->isAJAX()) {
            $id = decryptID($id_anggota);
            $cekAnggota = $this->anggotaModel->find($id);
            if (!$cekAnggota) {
                echo json_encode([
                    'error' => true,
                    'message' => "Anggota kos tidak ditemukan!"
                ]);
                return;
            }

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'nama' => [
                    'label' => 'Nama',
                    'rules' => 'required|max_length[150]',
                    // 'rules' => 'required|max_length[150]|is_unique[tbl_anggota.nama,id,' . $id . ']',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                        'is_unique' => '{field} sudah ada !',
                    ]
                ],
                'tgl_kost' => [
                    'label' => 'Tanggal Mulai Kos',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'jenis_sewa' => [
                    'label' => 'Jenis Sewa',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'telp' => [
                    'label' => 'No telp/WA',
                    'rules' => 'required|max_length[15]|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 14 karakter !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'telp_kerabat' => [
                    'label' => 'No telp/WA (orang tua/kerabat)',
                    'rules' => 'required|max_length[15]|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 14 karakter !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'alamat' => [
                    'label' => 'Alamat',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'keterangan' => [
                    'label' => 'Keterangan',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'id_kamar' => [
                    'label' => 'Kamar Kos',
                    // 'rules' => 'required|is_unique[tbl_anggota.id_kamar,id,' . $id . ']',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'is_unique' => "{field} sudah diisi/digunakan !"
                    ]
                ],
                'biaya_tambahan' => [
                    'label' => 'Biaya Tambahan',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
            ]);

            if (!$valid) {
                exit(json_encode([
                    'error' => true,
                    'message' => "Validation Failed",
                    'errors' => $validation->getErrors()
                ]));
            }

            $data = trimAllPostInput($this->request->getPost());
            $data['nama'] = strtoupper($data['nama']);
            $save = $this->anggotaModel->update($id, $data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Anggota kos " . $data['nama'] . " berhasil diubah"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Anggota kos " . $data['nama'] . " gagal diubah!"
                ]);
            }
        }
    }

    public function hapus($id)
    {
        try {
            $id = decryptID($id);
            // buat saja active nya kosong 
            // $this->anggotaModel->delete($id);

            // jadikan statusnya 0
            $this->anggotaModel->update($id, [
                'active' => 0,
                'tanggal_tidak_aktif' => date("Y-m-d"),
            ]);

            echo json_encode([
                'success' => true,
                'message' => "Anggota berhasil dinonaktifkan"
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'error' => true,
                'message' => "Terdapat kesalahan pada sistem!"
            ]);
        }
    }

    public function aktifkan_anggota($id)
    {
        $id = decryptID($id);

        // cek transaksi kost terakhir
        $pembayaranModel = new PembayaranModel();
        $cekPembayaranTerakhir = $pembayaranModel->where('id_anggota', $id)->orderBy('jatuh_tempo', 'DESC')->first();
        if ($cekPembayaranTerakhir) {
            $tanggal_kost_terakhir = date("Y-m", strtotime($cekPembayaranTerakhir['jatuh_tempo']));
            if (date("Y-m") == $tanggal_kost_terakhir) {
                $tgl_baru = $cekPembayaranTerakhir['jatuh_tempo'];
            } else {
                $tgl_baru = date("Y-m-d");
            }
        } else {
            // jika tidak ada cek tgl anggota mulai mendaftar
            $cekAnggota = $this->anggotaModel->find($id);
            $tanggal_kost = date("Y-m", strtotime($cekPembayaranTerakhir['tgl_kost']));
            if (date("Y-m") == $tanggal_kost) {
                $tgl_baru = $cekAnggota['tgl_kost'];
            } else {
                $tgl_baru = date("Y-m-d");
            }
        }

        $this->anggotaModel->update($id, [
            'tgl_kost' => $tgl_baru,
            'active' => 1,
            'tanggal_tidak_aktif' => null,
        ]);

        echo json_encode([
            'success' => true,
            'message' => "Anggota berhasil diaktifkan"
        ]);
    }

    public function export_excel()
    {
        $data = $this->anggotaModel->getAllAnggota()->get()->getResultArray();
        return view('anggota/v_anggota_export_excel', [
            'data' => $data,
        ]);
    }
}
