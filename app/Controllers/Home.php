<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\AnggotaModel;
use App\Models\DetailPembayaranModel;
use App\Models\KamarModel;

class Home extends BaseController
{
    public function index()
    {
        $detailPembayaranModel = new DetailPembayaranModel();
        $anggotaModel = new AnggotaModel();
        $kamarModel = new KamarModel();
        $adminModel = new AdminModel();
        $currUser = $adminModel->find(decryptID(session("LoggedUserData")['id_admin']));
        $data = [
            'title' => "Dashboard",
            'total_pemasukan' => $detailPembayaranModel->allTotalBayarKos(date("Y")),
            'total_anggota_kos' => $anggotaModel->getTotalAnggotaAKtif(),
            'total_kamar_tersedia' => $kamarModel->getTotalKamarTersedia(),
            'currUser' => $currUser,
            'tahun' => date('Y'),
        ];
        return view('dashboard/v_dashboard', $data);
    }
}
