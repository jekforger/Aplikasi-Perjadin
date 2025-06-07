<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pengusulanController; // Anda mungkin tidak menggunakan ini jika pengusulanController sudah direname/digabung
use App\Http\Controllers\MenuPengusulanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengusulController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\DataMahasiswaController;

// Halaman awal untuk memilih role (akan diakses di root URL: '/')
Route::get('/', [LoginController::class, 'showSelectRoleForm'])->name('login.select-role');

// Halaman form login (misal: /login?role=pengusul)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Proses POST data login
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

// Proses Logout (akan dipanggil dari tombol logout di navbar)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Rute untuk Role Pengusul (Sudah Pernah Dibahas, Kita Pakai Lagi) ---
// Nantinya akan ada middleware 'auth' dan 'role' di grup ini
Route::prefix('pengusul')->name('pengusul.')->group(function () {
    Route::get('/dashboard', [PengusulController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengusulan', [PengusulController::class, 'pengusulan'])->name('pengusulan');
    Route::post('/pengusulan', [PengusulController::class, 'storePengusulan'])->name('store.pengusulan');
    // Rute placeholder untuk menu lain di sidebar Pengusul
    Route::get('/status', [PengusulController::class, 'status'])->name('status');
    Route::get('/draft', [PengusulController::class, 'draft'])->name('draft');
    Route::get('/history', [PengusulController::class, 'history'])->name('history');
    Route::get('/pilihpengusul', [PengusulController::class, 'pilih'])->name('pilih');

    
    // Route::get('/history', function() { return 'Halaman History Pengusul (belum dibuat)'; })->name('history');
});


// --- Rute Placeholder untuk Role Lain (akan dikembangkan nanti) ---
// Ini hanya untuk memastikan link redirect di LoginController tidak error "route not defined"
Route::prefix('pelaksana')->name('pelaksana.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Pelaksana (belum dibuat)'; })->name('dashboard');
});
Route::prefix('bku')->name('bku.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard BKU (belum dibuat)'; })->name('dashboard');
});
// Rute untuk semua Wakil Direktur (I-IV) mengarah ke dashboard Wadir
Route::prefix('wadir')->name('wadir.')->group(function () {
    // Satu rute dashboard umum untuk semua Wadir I-IV
    Route::get('/dashboard', function() { return 'Dashboard Wakil Direktur (belum dibuat)'; })->name('dashboard');
});
Route::prefix('direktur')->name('direktur.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Direktur (belum dibuat)'; })->name('dashboard');
});
Route::prefix('sekdir')->name('sekdir.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Sekretaris Direktur (belum dibuat)'; })->name('dashboard');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/datapegawai', [DataPegawaiController::class, 'index'])->name('datapegawai');
    Route::resource('pegawai', DataPegawaiController::class);
    Route::get('/datamahasiswa', [DataMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::resource('mahasiswa', DataMahasiswaController::class);

});

// Route::get('/', function () {
//     return view('auth/login');
// });

// Jika pengusulanController adalah controller yang sama atau berbeda, sesuaikan.
// Route::get('/pengusul', [pengusulanController::class, 'index']); // Baris ini mungkin untuk halaman lain

// Rute untuk menampilkan form pengusulan
// Route::get('/pengusulan', [MenuPengusulanController::class, 'pengusulan'])->name('pengusulan.form'); // Beri nama agar mudah dipanggil

// Rute untuk MENYIMPAN data dari form pengusulan
// Route::post('/pengusulan', [MenuPengusulanController::class, 'storePengusulan'])->name('pengusulan.store');