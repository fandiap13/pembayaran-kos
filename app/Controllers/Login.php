<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;

class Login extends BaseController
{
    public function index()
    {
        if ($this->request->getPost()) {
            $valid = $this->validate([
                'username' => [
                    'label' => 'Username',
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
            ]);

            if (!$valid) {
                return redirect()->to(base_url('login'));
            }

            $data = trimAllPostInput($this->request->getPost());
            $adminModel = new AdminModel();
            $cek_user = $adminModel->where('username', $data['username'])->first();
            if (!$cek_user) {
                session()->setFlashdata('msg', "danger#Username tidak ditemukan!");
                return redirect()->to(base_url("login"));
            }

            if (!password_verify($data['password'], $cek_user['password'])) {
                session()->setFlashdata('msg', "danger#Password yang anda masukkan salah!");
                return redirect()->to(base_url("login"));
            }

            $sessionData = [
                'id_admin' => encryptID($cek_user['id']),
                'username' => $cek_user['username'],
                'nama' => $cek_user['nama'],
                'waktu_login' => date('Y-m-d H:i:s'),
            ];
            session()->set("LoggedUserData", $sessionData);
            return redirect()->to(base_url('dashboard'));
        }

        return view("v_login");
    }
}
