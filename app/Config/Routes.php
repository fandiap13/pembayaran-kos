<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// auth
$routes->get('login', 'Login::index');
$routes->post('login', 'Login::index');
$routes->get('logout', 'Logout::index');

$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::index');
// kategori
$routes->group('kategori', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Kategori::index');
    $routes->get('detail/(:any)', 'Kategori::detail/$1');
    $routes->post('create', 'Kategori::create');
    $routes->post('update/(:any)', 'Kategori::update/$1');
    $routes->get('hapus/(:any)', 'Kategori::hapus/$1');
});
// kamar
$routes->group('kamar', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Kamar::index');
    $routes->get('detail/(:any)', 'Kamar::detail/$1');
    $routes->post('create', 'Kamar::create');
    $routes->post('update/(:any)', 'Kamar::update/$1');
    $routes->get('hapus/(:any)', 'Kamar::hapus/$1');
});
// admin
$routes->group('admin', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('detail/(:any)', 'Admin::detail/$1');
    $routes->post('create', 'Admin::create');
    $routes->post('update/(:any)', 'Admin::update/$1');
    $routes->get('hapus/(:any)', 'Admin::hapus/$1');
});
// anggota
$routes->group('anggota', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Anggota::index');
    $routes->get('tambah', 'Anggota::tambah');
    $routes->get('edit/(:any)', 'Anggota::edit/$1');
    $routes->get('aktifkan_anggota/(:any)', 'Anggota::aktifkan_anggota/$1');
    $routes->get('anggota_datatable', 'Anggota::anggota_datatable');
    $routes->get('detail/(:any)', 'Anggota::detail/$1');
    $routes->post('create', 'Anggota::create');
    $routes->post('update/(:any)', 'Anggota::update/$1');
    $routes->get('hapus/(:any)', 'Anggota::hapus/$1');
    $routes->get('export_excel', 'Anggota::export_excel');
});
// anggota tidak aktif
$routes->group('anggota_tidak_aktif', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Anggota::anggota_tidak_aktif');
    $routes->get('anggota_tidak_aktif_datatable', 'Anggota::anggota_tidak_aktif_datatable');
});
// pembayaran
$routes->group('pembayaran', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'Pembayaran::index');
    $routes->get('pembayaran_datatable/(:any)/(:any)', 'Pembayaran::pembayaran_datatable/$1/$2');
    $routes->get('default/(:any)/(:any)', 'Pembayaran::pembayaran/$1/$2');
    $routes->post('tambah_pembayaran', 'Pembayaran::tambah_pembayaran');
    $routes->post('tambah_pelunasan', 'Pembayaran::tambah_pelunasan');
    $routes->get('hapus_detail_pembayaran/(:any)', 'Pembayaran::hapus_detail_pembayaran/$1');
    $routes->get('hapus_transaksi_pembayaran/(:any)', 'Pembayaran::hapus_transaksi_pembayaran/$1');
    $routes->get('cetak_kuitansi/(:any)', 'Pembayaran::cetak_kuitansi/$1');
});
// riwayat pembayaran
$routes->group('riwayat_pembayaran', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('/', 'RiwayatPembayaran::index');
    $routes->get('riwayat_pembayaran_datatable/(:any)', 'RiwayatPembayaran::pembayaran_datatable/$1');
    $routes->get('export', 'RiwayatPembayaran::export');
});
