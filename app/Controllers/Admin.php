<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Admin extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        return view('admin/v_index', [
            'title' => "Admin Kos",
            'data' => $this->adminModel->orderBy('nama', 'asc')->findAll()
        ]);
    }

    public function detail($id)
    {
        try {
            $detailadmin = $this->adminModel->find(decryptID($id));
            if (!$detailadmin) {
                echo json_encode([
                    'error' => true,
                    'message' => "Admin tidak ditemukan!"
                ]);
            }
            echo json_encode([
                'success' => true,
                'data' => $detailadmin,
                'message' => "Admin berhasil ditampilkan"
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
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required|max_length[150]|is_unique[tbl_admin.username]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                        'is_unique' => '{field} sudah ada !',
                    ]
                ],
                'nama' => [
                    'label' => 'Nama',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'telp' => [
                    'label' => 'No Telp',
                    'rules' => 'permit_empty|max_length[14]|numeric',
                    'errors' => [
                        'max_length' => '{field} maksimal 14 karakter !',
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
            $data['username'] = strtoupper($data['username']);
            $data['nama'] = strtoupper($data['nama']);
            $data['password'] = strtoupper($data['password']);
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $save = $this->adminModel->insert($data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Admin " . $data['username'] . " berhasil disimpan"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Admin " . $data['username'] . " gagal disimpan!"
                ]);
            }
        }
    }

    public function update($id)
    {
        if ($this->request->isAJAX()) {
            $cekAdmin = $this->adminModel->find(decryptID($id));
            if (!$cekAdmin) {
                echo json_encode([
                    'error' => true,
                    'message' => "Admin tidak ditemukan!"
                ]);
                return;
            }

            $validation = \Config\Services::validation();
            $valid = $this->validate([
                'username' => [
                    'label' => 'Username',
                    'rules' => 'required|max_length[150]|is_unique[tbl_admin.username,id,' . $cekAdmin['id'] . ']',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                        'is_unique' => '{field} sudah ada !',
                    ]
                ],
                'nama' => [
                    'label' => 'Nama',
                    'rules' => 'required|max_length[150]',
                    'errors' => [
                        'required' => '{field} harus diisi !',
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'password' => [
                    'label' => 'Password',
                    'rules' => 'permit_empty|max_length[150]',
                    'errors' => [
                        'max_length' => '{field} maksimal 150 karakter !',
                    ]
                ],
                'telp' => [
                    'label' => 'No Telp',
                    'rules' => 'permit_empty|max_length[14]|numeric',
                    'errors' => [
                        'max_length' => '{field} maksimal 14 karakter !',
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
            $data['password'] = strtoupper($data['password']);
            if ($data['password'] != "" && $data['password'] != null) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                unset($data['password']);
            }

            $data['username'] = strtoupper($data['username']);
            $data['nama'] = strtoupper($data['nama']);
            $save = $this->adminModel->update($cekAdmin['id'], $data);
            if ($save) {
                echo json_encode([
                    'success' => true,
                    'message' => "Admin " . $data['username'] . " berhasil diubah"
                ]);
            } else {
                echo json_encode([
                    'error' => true,
                    'message' => "Admin " . $data['username'] . " gagal diubah!"
                ]);
            }
        }
    }

    public function hapus($id)
    {
        try {
            $this->adminModel->delete(decryptID($id));
            echo json_encode([
                'success' => true,
                'message' => "Admin berhasil dihapus"
            ]);
        } catch (\Throwable $th) {
            echo json_encode([
                'error' => true,
                'message' => "Terdapat kesalahan pada sistem!"
            ]);
        }
    }
}
