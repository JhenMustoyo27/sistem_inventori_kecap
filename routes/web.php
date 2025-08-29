<?php

use App\Http\Controllers\Admin\StokMasukController;
use App\Http\Controllers\Admin\MasterStokController;
use App\Http\Controllers\Admin\StokKeluarController;
use App\Http\Controllers\Admin\LaporanController; // Admin LaporanController
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\KelolaAkunController;
use App\Http\Controllers\Pemilik\LaporanController as LaporanControllerPemilik; // Alias untuk Pemilik LaporanController
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

// Rute Halaman Utama & Login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Grup Rute untuk Pengguna yang Sudah Login
Route::middleware(['auth'])->group(function () {
    
    // Logout harus di dalam grup auth
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Grup Rute Khusus Admin
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Rute untuk Stok Masuk
        Route::get('/stok-masuk', [StokMasukController::class, 'index'])->name('stok_masuk.index');
        Route::post('/stok-masuk', [StokMasukController::class, 'store'])->name('stok_masuk.store');
        Route::get('/stok-masuk/{kecapMasuk}/edit', [StokMasukController::class, 'edit'])->name('stok_masuk.edit');
        Route::put('/stok-masuk/{kecapMasuk}', [StokMasukController::class, 'update'])->name('stok_masuk.update');
        Route::delete('/stok-masuk/{kecapMasuk}', [StokMasukController::class, 'destroy'])->name('stok_masuk.destroy');
        Route::get('/stok-masuk/get-next-code', [StokMasukController::class, 'getNextCode'])->name('admin.stok_masuk.getNextCode');

        // Rute untuk Master Stok (Kelola Stok)
        Route::get('/master-stok', [MasterStokController::class, 'index'])->name('master_stok.index');
        Route::post('/master-stok', [MasterStokController::class, 'store'])->name('master_stok.store');
        Route::get('/master-stok/{masterStok}/edit', [MasterStokController::class, 'edit'])->name('master_stok.edit');
        Route::put('/master-stok/{masterStok}', [MasterStokController::class, 'update'])->name('master_stok.update');
        Route::delete('/master-stok/{masterStok}', [MasterStokController::class, 'destroy'])->name('master_stok.destroy');

        // Rute baru untuk melihat histori stok
        Route::get('/master-stok/{kecapMasukId}/history', [MasterStokController::class, 'showHistory'])->name('master_stok.history');
        
        // Rute untuk Stok Keluar
        Route::get('/stok-keluar', [StokKeluarController::class, 'index'])->name('stok_keluar.index');
        Route::post('/stok-keluar', [StokKeluarController::class, 'store'])->name('stok_keluar.store');
        // BARU: Rute untuk Edit, Update, dan Delete Stok Keluar
        Route::get('/stok-keluar/{stokKeluar}/edit', [StokKeluarController::class, 'edit'])->name('stok_keluar.edit');
        Route::put('/stok-keluar/{stokKeluar}', [StokKeluarController::class, 'update'])->name('stok_keluar.update');
        Route::delete('/stok-keluar/{stokKeluar}', [StokKeluarController::class, 'destroy'])->name('stok_keluar.destroy');
        // Rute AJAX untuk mendapatkan ukuran dan harga jual
        Route::get('/get-ukuran-dan-harga-by-kecap-id', [StokKeluarController::class, 'getUkuranDanHargaByKecapId'])->name('get_ukuran_dan_harga_by_kecap_id');
        // BARU: Rute AJAX untuk mendapatkan info master stok spesifik (digunakan di halaman edit)
        Route::get('/get-master-stok-info', [StokKeluarController::class, 'getMasterStokInfo'])->name('get_master_stok_info');


        // Rute untuk Laporan Admin
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/download-pdf', [LaporanController::class, 'downloadPdf'])->name('laporan.download_pdf');
        
        // Rute untuk Laporan Expired Admin
        Route::get('/laporan/expired', [LaporanController::class, 'expired'])->name('laporan.expired');
        Route::get('/laporan/expired/download-pdf', [LaporanController::class, 'downloadExpiredPdf'])->name('laporan.download_expired_pdf');
    });

    // Grup Rute Khusus Pemilik
    Route::middleware(['pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
        Route::get('/dashboard', [PemilikDashboardController::class, 'index'])->name('dashboard');
        
        // BENAR: tanpakan lagi 'pemilik.' karena sudah didefinisikan di atas
        Route::resource('kelola-akun', KelolaAkunController::class)->parameters([
        'kelola-akun' => 'user', // Ini memberitahu Laravel bahwa parameter untuk resource 'kelola-akun' adalah 'user'
        ]);
        // Route::get('/kelola-akun', [KelolaAkunController::class, 'index'])->name('kelola_akun.index');
        // Route::get('/kelola-akun/create', [KelolaAkunController::class, 'create'])->name('kelola_akun.create');
        // Route::post('/kelola-akun', [KelolaAkunController::class, 'store'])->name('kelola_akun.store');
        // Route::get('/kelola-akun/{user}/edit', [KelolaAkunController::class, 'edit'])->name('kelola_akun.edit');
        // Route::put('/kelola-akun/{user}/update', [KelolaAkunController::class, 'update'])->name('kelola_akun.update');
        // Route::delete('/kelola-akun/{user}/delete', [KelolaAkunController::class, 'destroy'])->name('kelola_akun.destroy');

        // Rute untuk Laporan Akhir Pemilik
        Route::get('/laporan/akhir', [LaporanControllerPemilik::class, 'index'])->name('laporan.index');
        Route::get('/laporan/akhir/download-pdf', [LaporanControllerPemilik::class, 'downloadPdf'])->name('laporan.download_pdf');
        
        // Rute untuk Laporan Expired Pemilik (SEKARANG MENGGUNAKAN CONTROLLER PEMILIK)
        Route::get('/laporan/expired', [LaporanControllerPemilik::class, 'expired'])->name('laporan.expired');
        Route::get('/laporan/expired/download-pdf', [LaporanControllerPemilik::class, 'downloadExpiredPdf'])->name('laporan.download_expired_pdf');
    });

});
