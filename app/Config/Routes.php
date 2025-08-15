<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ========================
// ROUTES UTAMA SISWA
// ========================

// Halaman awal & daftar siswa
$routes->get('/', 'PengunjungController::index');
$routes->get('/login', 'Auth::login'); // Route default diarahkan ke login
$routes->get('/siswa', 'SiswaController::index'); // Daftar siswa
// Tambah siswa
$routes->get('/siswa/create', 'SiswaController::create'); // Form tambah
$routes->post('/siswa/store', 'SiswaController::store'); // Proses simpan
// Edit & update siswa
$routes->get('/siswa/edit/(:num)', 'SiswaController::edit/$1'); // Form edit
$routes->post('/siswa/update/(:num)', 'SiswaController::update/$1'); // Proses update

// Hapus siswa
$routes->get('siswa/hapus/(:num)', 'SiswaController::delete/$1'); // Proses hapus

// Export data siswa ke PDF/Excel
$routes->get('/siswa/export-pdf', 'SiswaController::exportPdf'); // Export PDF
$routes->get('/siswa/export-excel', 'SiswaController::exportExcel'); // Export Excel

// ========================
// ROUTES LAPORAN SISWA
// ========================

// Menampilkan halaman laporan siswa (data semua siswa)
$routes->get('siswa/laporan_siswa', 'SiswaController::laporanSiswa'); // View laporan

// Export laporan dalam bentuk PDF/Excel
$routes->get('siswa/export-laporan-siswa-pdf', 'SiswaController::exportLaporanSiswaPdf'); // Export PDF
$routes->get('siswa/export-laporan-siswa-excel', 'SiswaController::exportLaporanExcel'); // Export Excel

// ========================
// ROUTES MANAJEMEN KELAS
// ========================

// Halaman daftar kelas
$routes->get('/kelas', 'KelasController::index');

// Tambah kelas
$routes->get('/kelas/create', 'KelasController::create');
$routes->post('/kelas/store', 'KelasController::store');

// Edit & update kelas
$routes->get('/kelas/edit/(:num)', 'KelasController::edit/$1');
$routes->post('/kelas/update/(:num)', 'KelasController::update/$1');

// Hapus kelas
$routes->get('/kelas/delete/(:num)', 'KelasController::delete/$1');

// Export data kelas
$routes->get('/kelas/exportPdf', 'KelasController::exportPdf');
$routes->get('/kelas/exportExcel', 'KelasController::exportExcel');

// ========================
// ROUTES Manajemen Ortu
// ========================
// Halaman daftar orang tua
$routes->get('/ortu', 'OrtuController::index');
$routes->get('/ortu/create', 'OrtuController::create');
$routes->post('/ortu/store', 'OrtuController::store');
$routes->get('/ortu/edit/(:num)', 'OrtuController::edit/$1');
$routes->post('/ortu/update/(:num)', 'OrtuController::update/$1');
$routes->add('/ortu/update/(:num)', 'OrtuController::update/$1', ['filter' => 'csrf']);
$routes->get('/ortu/delete/(:num)', 'OrtuController::delete/$1');

// ========================
// ROUTES PENGUNJUNG
// ========================
$routes->get('pengunjung', 'PengunjungController::index');
$routes->get('pengunjung/index', 'PengunjungController::index');
$routes->get('pengunjung/hubungi', 'PengunjungController::hubungi');
$routes->post('pengunjung/kirim-pengajuan', 'PengunjungController::kirim_pengajuan');
$routes->get('pengunjung/petasiswa', 'PengunjungController::petasiswa');
// == ROUTE BARU UNTUK EKSPOR ==
$routes->get('pengunjung/export-excel', 'PengunjungController::export_excel');
$routes->get('pengunjung/export-pdf', 'PengunjungController::export_pdf');
$routes->get('pengunjung/list-siswa', 'PengunjungController::listSiswa');



$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::loginPost');

// ========================
// ROUTES USER (CRUD)
// ========================

$routes->get('/user', 'User::index');
$routes->get('/user/create', 'User::create');
$routes->post('/user/store', 'User::store');
$routes->get('/user/edit/(:num)', 'User::edit/$1');
$routes->post('/user/update/(:num)', 'User::update/$1');
$routes->get('/user/delete/(:num)', 'User::delete/$1');

// ========================
// ROUTES peta
// ========================

$routes->get('/peta-sebaran', 'PetaController::index');


// ========================
// ROUTES LOG PENGATURAN
// ========================
$routes->get('/pengaturan/log', 'LogController::log'); // View log page
$routes->get('/pengaturan/log/(:any)', 'LogController::log/$1'); // View log by type
$routes->post('/pengaturan/log/clear', 'LogController::clearLogs'); // Hapus log


// Pengaturan Routes
$routes->group('pengaturan', ['filter' => 'auth'], function ($routes) {
    // Lokasi Routes
    $routes->get('lokasi', 'PengaturanController::lokasi');
    $routes->get('lokasi/create', 'PengaturanController::lokasiCreate');
    $routes->post('lokasi/store', 'PengaturanController::lokasiStore');
    $routes->get('lokasi/edit/(:num)', 'PengaturanController::lokasiEdit/$1');
    $routes->put('lokasi/update/(:num)', 'PengaturanController::lokasiUpdate/$1');
    $routes->delete('lokasi/delete/(:num)', 'PengaturanController::lokasiDelete/$1');
});

$routes->setAutoRoute(true);
