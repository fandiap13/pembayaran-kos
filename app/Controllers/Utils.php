<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\AnggotaModel;
use App\Models\DetailPembayaranModel;
use App\Models\KamarModel;
use App\Models\KategoriModel;
use App\Models\PembayaranModel;
use Ifsnop\Mysqldump\Mysqldump;

class Utils extends BaseController
{
    public function index()
    {
        return view("utils/v_index", [
            'title' => "Utils",
        ]);
    }

    public function backup_database()
    {
        try {
            $currDate = date("Y-m-d_H-i");
            $folderName = date("Y-m-d");
            // Tentukan path lengkap ke folder "cek" di dalam folder "public/database"
            $folderPath = FCPATH . 'database/' . $folderName;
            if (!is_dir($folderPath)) {
                // Jika folder belum ada, buat folder baru
                mkdir($folderPath, 0777, true); // 0777 adalah mode izin untuk folder baru
            }

            $dump = new Mysqldump(
                'mysql:host=' . getenv('database.default.hostname') . ';dbname=' . getenv('database.default.database') . ';port=' . getenv('database.default.port'),
                getenv("database.default.username"),
                getenv("database.default.password")
            );

            $dump->start($folderPath . "/db_kos_" . $currDate . ".sql");

            // Buat objek ZipArchive
            $zip = new \ZipArchive();
            $zipFileName = 'backup_database_' .  $currDate . '.zip'; // Nama file zip yang akan diunduh
            $imageFolderPath1 = FCPATH . 'database/'; // Ubah sesuai dengan path folder pertama

            // Buat file zip baru
            if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                // Menambahkan semua file di dalam folder pertama ke dalam file zip
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($imageFolderPath1));
                foreach ($files as $file) {
                    // Pastikan itu adalah file dan bukan direktori
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        // Menambahkan file ke dalam zip dengan path relatif dari folder gambar
                        $relativePath = substr($filePath, strlen($imageFolderPath1));
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                // Tutup file zip
                $zip->close();

                // Tentukan header untuk file zip yang akan diunduh
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
                header('Content-Length: ' . filesize($zipFileName));

                // Mengirimkan file zip ke browser
                readfile($zipFileName);

                // Hapus file zip setelah diunduh
                unlink($zipFileName);
                // session()->setFlashdata("msg", "success#Berhasil backup folder public");
            } else {
                // Jika gagal membuat file zip
                echo 'Failed to create zip file.';
            }
        } catch (\Exception $e) {
            $pesan = "BACKUP ERROR: " . $e->getMessage();
            echo $pesan;
        }
    }

    public function backup_reset_database()
    {
        try {
            $currDate = date("Y-m-d_H-i");
            $folderName = date("Y-m-d");
            // Tentukan path lengkap ke folder "cek" di dalam folder "public/database"
            $folderPath = FCPATH . 'database/' . $folderName;
            if (!is_dir($folderPath)) {
                // Jika folder belum ada, buat folder baru
                mkdir($folderPath, 0777, true); // 0777 adalah mode izin untuk folder baru
            }

            $dump = new Mysqldump(
                'mysql:host=' . getenv('database.default.hostname') . ';dbname=' . getenv('database.default.database') . ';port=' . getenv('database.default.port'),
                getenv("database.default.username"),
                getenv("database.default.password")
            );

            $dump->start($folderPath . "/db_kos_" . $currDate . ".sql");

            // Buat objek ZipArchive
            $zip = new \ZipArchive();
            $zipFileName = 'backup_database_' .  $currDate . '.zip'; // Nama file zip yang akan diunduh
            $imageFolderPath1 = FCPATH . 'database/'; // Ubah sesuai dengan path folder pertama

            // Buat file zip baru
            if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) {
                // Menambahkan semua file di dalam folder pertama ke dalam file zip
                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($imageFolderPath1));
                foreach ($files as $file) {
                    // Pastikan itu adalah file dan bukan direktori
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        // Menambahkan file ke dalam zip dengan path relatif dari folder gambar
                        $relativePath = substr($filePath, strlen($imageFolderPath1));
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                // Tutup file zip
                $zip->close();

                // Tentukan header untuk file zip yang akan diunduh
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
                header('Content-Length: ' . filesize($zipFileName));

                // Mengirimkan file zip ke browser
                readfile($zipFileName);

                // Hapus file zip setelah diunduh
                unlink($zipFileName);
                // session()->setFlashdata("msg", "success#Berhasil backup folder public");

                // REMOVE SEMUA DATA TABEL
                $db = db_connect();
                // Nonaktifkan pengecekan foreign key constraints
                $db->disableForeignKeyChecks();
                $detailPembayaranModel = new DetailPembayaranModel();
                $pembayaranModel = new PembayaranModel();
                $anggotaModel = new AnggotaModel();
                $kamarModel = new KamarModel();
                $kategoriKamarModel = new KategoriModel();
                $adminModel = new AdminModel();

                $detailPembayaranModel->truncate();
                $pembayaranModel->truncate();
                $anggotaModel->truncate();
                $kamarModel->truncate();
                $kategoriKamarModel->truncate();
                // hapus semua tabel admin kecuali admin yang login saat ini
                $adminModel->where('id !=', decryptID(session("LoggedUserData")['id_admin']))->delete();
            } else {
                // Jika gagal membuat file zip
                echo 'Failed to create zip file.';
            }
        } catch (\Exception $e) {
            $pesan = "BACKUP ERROR: " . $e->getMessage();
            echo $pesan;
        }
    }
}
