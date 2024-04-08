<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\KategoriModel;

class Kamar extends BaseController
{
    protected $kamarModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->kamarModel = new KamarModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        return view('kamar/v_index', [
            'title' => "Kamar Kos",
            'kategori' => $this->kategoriModel->findAll(),
            'data' => $this->kamarModel->select('tbl_kamar.*,tbl_kategori_kamar.kategori')
                ->join('tbl_kategori_kamar', 'tbl_kamar.id_kategori=tbl_kategori_kamar.id')
                ->orderBy('tbl_kamar.nama', 'ASC')
                ->findAll()
        ]);
    }

    public function detail($id)
    {
        try {
            $detailKategori = $this->kamarModel->find($id);
            if (!$detailKategori) {
                echo json_encode([
                    'error' => true,
                    'message' => "Kategori tidak ditemukan!"
                ]);
            }
            echo json_encode([
                'success' => true,
                'data' => $detailKategori,
                'message' => "Kategori berhasil ditampilkan"
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
                    'label' => 'No.Kamar',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'spesifikasi' => [
                    'label' => 'Spesifikasi',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'harga' => [
                    'label' => 'Harga',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'lantai' => [
                    'label' => 'Lantai',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'id_kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
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
            $save = $this->kamarModel->insert($data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Kamar " . $data['nama'] . " berhasil disimpan"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Kamar " . $data['nama'] . " gagal disimpan!"
                ]);
            }
        }
    }

    public function update($id)
    {
        if ($this->request->isAJAX()) {
            $cekKategori = $this->kamarModel->find($id);
            if (!$cekKategori) {
                echo json_encode([
                    'error' => true,
                    'message' => "Kategori tidak ditemukan!"
                ]);
                return;
            }

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'nama' => [
                    'label' => 'No.Kamar',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'spesifikasi' => [
                    'label' => 'Spesifikasi',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'harga' => [
                    'label' => 'Harga',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'numeric' => '{field} harus berupa angka !'
                    ]
                ],
                'lantai' => [
                    'label' => 'Lantai',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                    ]
                ],
                'id_kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required',
                    'errors' => [
                        'required' => '{field} harus diisi !',
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
            $save = $this->kamarModel->update($id, $data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Kamar berhasil diubah"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Kamar gagal diubah!"
                ]);
            }
        }
    }

    public function hapus($id)
    {
        try {
            $this->kamarModel->delete($id);
            echo json_encode([
                'success' => true,
                'message' => "Kategori berhasil dihapus"
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'error' => true,
                'message' => "Terdapat kesalahan pada sistem!"
            ]);
        }
    }
}
