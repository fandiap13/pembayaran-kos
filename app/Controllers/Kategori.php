<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KategoriModel;

class Kategori extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        return view('kategori/v_index', [
            'title' => "Kategori Kamar",
            'data' => $this->kategoriModel->orderBy('kategori', 'asc')->findAll()
        ]);
    }

    public function detail($id)
    {
        try {
            $detailKategori = $this->kategoriModel->find($id);
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
                'kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required|max_length[101]|is_unique[tbl_kategori_kamar.kategori]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 100 karakter !',
                        'is_unique' => '{field} sudah ada !',
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
            // $data['kategori'] = strtoupper($data['kategori']);
            $save = $this->kategoriModel->insert($data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Kategori berhasil disimpan"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Kategori gagal disimpan!"
                ]);
            }
        }
    }

    public function update($id)
    {
        if ($this->request->isAJAX()) {
            $cekKategori = $this->kategoriModel->find($id);
            if (!$cekKategori) {
                echo json_encode([
                    'error' => true,
                    'message' => "Kategori tidak ditemukan!"
                ]);
                return;
            }

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'kategori' => [
                    'label' => 'Kategori',
                    'rules' => 'required|max_length[101]|is_unique[tbl_kategori_kamar.kategori,id,' . $id . ']',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 100 karakter !',
                        'is_unique' => '{field} sudah ada !',
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
            // $data['kategori'] = strtoupper($data['kategori']);
            $save = $this->kategoriModel->update($id, $data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Kategori berhasil diubah"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Kategori gagal diubah!"
                ]);
            }
        }
    }

    public function hapus($id)
    {
        try {
            $this->kategoriModel->delete($id);
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
