<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function index()
    {
        session()->remove('LoggedUserData');

        session()->setFlashData("msg", 'success#Anda Berhasil Keluar');
        return redirect()->to(base_url('login'));
    }
}
