<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pengusulanController;
use App\Http\Controllers\MenuPengusulanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengusulController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\DataMahasiswaController;
use App\Http\Controllers\WadirController;
use App\Http\Controllers\PelaksanaController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParafController;

// Halaman awal untuk memilih role (akan diakses di root URL: '/')
Route::get('/', [LoginController::class, 'showSelectRoleForm'])->name('login.select-role');

// Halaman form login (misal: /login?role=pengusul)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Proses POST data login
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

// Proses Logout (akan dipanggil dari tombol logout di navbar)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [UserController::class, 'showChangePasswordForm'])->name('user.change-password.form');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('/paraf/upload', [ParafController::class, 'uploadDocument'])->name('paraf.upload');
});

// --- Rute untuk Role Pengusul ---
Route::prefix('pengusul')->name('pengusul.')->middleware('auth')->group(function () { // <-- TAMBAHKAN middleware('auth') DI SINI
    // Tambahkan middleware 'auth' di sini nanti setelah selesai pengembangan
    // ->middleware('auth', 'role:pengusul') // Middleware 'role' akan diterapkan nanti untuk otorisasi lebih lanjut
    Route::get('/dashboard', [PengusulController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengusulan', [PengusulController::class, 'pengusulan'])->name('pengusulan');
    Route::post('/pengusulan', [PengusulController::class, 'storePengusulan'])->name('store.pengusulan');
    Route::get('/status', [PengusulController::class, 'status'])->name('status');
    Route::get('/draft', [PengusulController::class, 'draft'])->name('draft');
    Route::get('/history', [PengusulController::class, 'history'])->name('history');
    Route::get('/pilihpengusul', [PengusulController::class, 'pilih'])->name('pilih');
});


// --- Rute Placeholder untuk Role Lain ---
Route::prefix('pelaksana')->name('pelaksana.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Pelaksana (belum dibuat)'; })->name('dashboard');
});
Route::prefix('bku')->name('bku.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard BKU (belum dibuat)'; })->name('dashboard');
});

// --- Rute untuk Wakil Direktur (I-IV) ---
Route::prefix('wadir')->name('wadir.')->group(function () {
    // Tambahkan middleware 'auth' dan 'role' di sini nanti
    // ->middleware('auth', 'role:wadir_1,wadir_2,wadir_3,wadir_4')
    Route::get('/dashboard', [WadirController::class, 'dashboard'])->name('dashboard');
    // Rute baru untuk detail review surat tugas
    Route::get('/surat-tugas/{id}/review', [WadirController::class, 'reviewSuratTugas'])->name('review.surat_tugas');
    // Rute baru untuk memproses keputusan Wadir (Setujui/Tolak/Revisi)
    Route::post('/surat-tugas/{id}/process-review', [WadirController::class, 'processSuratTugasReview'])->name('process.review.surat_tugas');
});
// Rute untuk role Wadir (tambahan link Paraf)
Route::prefix('wadir')->name('wadir.')->group(function () {
    Route::get('/dashboard', [WadirController::class, 'dashboard'])->name('dashboard');
    Route::get('/paraf', [ParafController::class, 'index'])->name('paraf'); // Link Paraf
    Route::get('/persetujuan', [WadirController::class, 'persetujuan'])->name('persetujuan');
});

Route::prefix('direktur')->name('direktur.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Direktur (belum dibuat)'; })->name('dashboard');
    Route::get('/dashboard', [DirekturController::class, 'dashboard'])->name('dashboard');
    Route::get('/paraf', [ParafController::class, 'index'])->name('paraf'); // Link Paraf
});
Route::prefix('sekdir')->name('sekdir.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Sekretaris Direktur (belum dibuat)'; })->name('dashboard');
});

// --- Rute untuk Admin ---
Route::prefix('admin')->name('admin.')->group(function () 
{
    // Tambahkan middleware 'auth' dan 'role' di sini nanti
    // ->middleware('auth', 'role:admin')
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/datapegawai', [DataPegawaiController::class, 'index'])->name('datapegawai');
    Route::resource('pegawai', DataPegawaiController::class);
    Route::get('/datamahasiswa', [DataMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::resource('mahasiswa', DataMahasiswaController::class);
});