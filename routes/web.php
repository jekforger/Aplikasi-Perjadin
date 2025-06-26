<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuPengusulanController; // Anda mungkin tidak menggunakan ini jika pengusulanController sudah direname/digabung
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengusulController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DataPegawaiController;
use App\Http\Controllers\DataMahasiswaController;
use App\Http\Controllers\WadirController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\PelaksanaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BkuController;

// Halaman awal untuk memilih role (akan diakses di root URL: '/')
Route::get('/', [LoginController::class, 'showSelectRoleForm'])->name('login.select-role');

// Halaman form login (misal: /login?role=pengusul)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// Proses POST data login
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

// Proses Logout (akan dipanggil dari tombol logout di navbar)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/change-password', [UserController::class, 'changePassword'])->name('user.change-password');


// --- Rute untuk Role Pengusul ---
Route::prefix('pengusul')->name('pengusul.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [PengusulController::class, 'dashboard'])->name('dashboard');
    Route::get('/pengusulan', [PengusulController::class, 'pengusulan'])->name('pengusulan');
    Route::post('/pengusulan', [PengusulController::class, 'storePengusulan'])->name('store.pengusulan');
    Route::get('/status', [PengusulController::class, 'status'])->name('status');
    Route::get('/draft', [PengusulController::class, 'draft'])->name('draft');
    Route::delete('/draft/{id}', [PengusulController::class, 'deleteDraft'])->name('draft.delete'); // <-- TAMBAHKAN INI
    Route::get('/history', [PengusulController::class, 'history'])->name('history');
    Route::get('/pilihpengusul', [PengusulController::class, 'pilih'])->name('pilih');
});


// --- Rute Placeholder untuk Role Lain ---
Route::prefix('pelaksana')->name('pelaksana.')->group(function () {
    Route::get('/dashboard', [PelaksanaController::class, 'dashboard'])->name('dashboard');
    Route::get('/bukti', [PelaksanaController::class, 'bukti'])->name('bukti');
    Route::get('/laporan', [PelaksanaController::class, 'laporan'])->name('laporan');
    Route::get('/dokumen', [PelaksanaController::class, 'dokumen'])->name('dokumen');
    Route::get('/statusLaporan', [PelaksanaController::class, 'statusLaporan'])->name('statusLaporan');
});
Route::prefix('bku')->name('bku.')->group(function () {
    Route::get('/dashboard', [BkuController::class, 'dashboard'])->name('dashboard');
    Route::get('/bukti', [BkuController::class, 'bukti'])->name('bukti');
    Route::get('/laporan', [BkuController::class, 'laporan'])->name('laporan');
});

// --- Rute untuk Wakil Direktur (I-IV) ---
Route::prefix('wadir')->name('wadir.')->group(function () {
    Route::get('/dashboard', [WadirController::class, 'dashboard'])->name('dashboard');

    // Rute baru untuk daftar Persetujuan
    Route::get('/persetujuan', [WadirController::class, 'persetujuan'])->name('persetujuan');
    // Rute baru untuk daftar Paraf
    Route::get('/paraf', [WadirController::class, 'paraf'])->name('paraf');

    // Rute untuk Halaman Upload Paraf
    Route::get('/paraf', [WadirController::class, 'paraf'])->name('paraf'); // GET untuk menampilkan form/preview
    Route::post('/paraf/upload', [WadirController::class, 'uploadParaf'])->name('paraf.upload'); // POST untuk proses upload
    Route::post('/paraf/delete', [WadirController::class, 'deleteParaf'])->name('paraf.delete'); // POST untuk proses hapus
    
    // Rute untuk detail review surat tugas
    Route::get('/surat-tugas/{id}/review', [WadirController::class, 'reviewSuratTugas'])->name('review.surat_tugas');
    // Rute untuk memproses keputusan Wadir
    Route::post('/surat-tugas/{id}/process-review', [WadirController::class, 'processSuratTugasReview'])->name('process.review.surat_tugas');
});

Route::prefix('direktur')->name('direktur.')->group(function () {
    // Tambahkan middleware 'auth' di sini nanti
    // ->middleware('auth', 'role:direktur')
    Route::get('/dashboard', [DirekturController::class, 'dashboard'])->name('dashboard');

    // Rute placeholder untuk Persetujuan Direktur (akan dibuat di fase selanjutnya)
    Route::get('/persetujuan', function() { return 'Halaman Persetujuan Direktur (belum dibuat)'; })->name('persetujuan');
    
    // Rute placeholder untuk Tanda Tangan/Paraf Direktur (akan dibuat di fase selanjutnya)
    Route::get('/paraf', function() { return 'Halaman Tanda Tangan Direktur (belum dibuat)'; })->name('paraf');
    // Jika nanti ada upload paraf Direktur, tambahkan route POST untuk itu
    // Route::post('/paraf/upload', [DirekturController::class, 'uploadParaf'])->name('paraf.upload');
    // Route::post('/paraf/delete', [DirekturController::class, 'deleteParaf'])->name('paraf.delete');

    // Rute placeholder untuk review surat tugas Direktur
    Route::get('/surat-tugas/{id}/review', function($id) { return "Review Surat Tugas Direktur ID: {$id} (belum dibuat)"; })->name('review.surat_tugas');
    // Rute placeholder untuk proses keputusan Direktur
    Route::post('/surat-tugas/{id}/process-review', function($id) { return "Proses Keputusan Direktur ID: {$id} (belum dibuat)"; })->name('process.review.surat_tugas');
});

Route::prefix('sekdir')->name('sekdir.')->group(function () {
    Route::get('/dashboard', function() { return 'Dashboard Sekretaris Direktur (belum dibuat)'; })->name('dashboard');
});

// --- Rute untuk Admin ---
Route::prefix('admin')->name('admin.')->group(function () {
    // Tambahkan middleware 'auth' dan 'role' di sini nanti
    // ->middleware('auth', 'role:admin')
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/datapegawai', [DataPegawaiController::class, 'index'])->name('datapegawai');
    Route::resource('pegawai', DataPegawaiController::class);
    Route::get('/datamahasiswa', [DataMahasiswaController::class, 'index'])->name('datamahasiswa');
    Route::resource('mahasiswa', DataMahasiswaController::class);
});

// Anda bisa membuat Controller terpisah untuk User Profile/Settings
Route::middleware('auth')->group(function () {
    // Rute untuk halaman Ganti Password
    Route::get('/user/change-password', function () {
        return view('user.change-password'); // Nanti Anda perlu membuat view ini
    })->name('user.change-password.form');

    // Contoh rute lain untuk profile jika ada
    Route::get('/user/profile', function () {
        return view('user.profile');
    })->name('user.profile');
});
