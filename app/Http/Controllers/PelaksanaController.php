<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import Auth facade
use App\Models\SuratTugas; // <-- Import model SuratTugas
use App\Models\Pegawai;    // <-- Import model Pegawai
use App\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;         // <-- Import Carbon untuk tanggal

class PelaksanaController extends Controller
{
    /**
     * Helper untuk meneruskan variabel global yang dibutuhkan oleh layout.
     */
    private function getGlobalViewData()
    {
        $userRole = Auth::user()->role;
        // Menggunakan app() untuk me-resolve controller dari service container
        $roleDisplayName = app(LoginController::class)->getRoleDisplayName($userRole);
        return compact('userRole', 'roleDisplayName');
    }

    /**
     * Menampilkan dashboard untuk Pelaksana Tugas.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Logika untuk menghubungkan User yang login ke record Pegawai
        $pegawaiId = $user->pegawai_id;

        // Jika user ini terhubung ke seorang pegawai, cari surat tugasnya.
        if ($pegawaiId) {
            $query = SuratTugas::whereHas('detailPelaksanaTugas', function ($q) use ($pegawaiId) {
                $q->where('personable_id', $pegawaiId)
                  ->where('personable_type', Pegawai::class);
            });
        } else {
            // Jika user ini (misalnya admin yang mencoba akses) tidak terhubung ke pegawai, kembalikan hasil kosong.
            $query = SuratTugas::where('surat_tugas_id', -1); // Query yang tidak akan mengembalikan hasil
        }

        // --- Menghitung Statistik Dashboard ---
        $totalPenugasan = (clone $query)->count();
        // Penugasan baru adalah yang statusnya diterbitkan dan belum lewat tanggal kembali
        $penugasanBaru = (clone $query)
            ->where('status_surat', 'diterbitkan')
            ->whereDate('tanggal_kembali', '>=', Carbon::today())
            ->count();
        // Laporan belum selesai adalah tugas yang sudah diterbitkan tapi statusnya belum 'laporan_selesai'
        $laporanBelumSelesai = (clone $query)
            ->whereIn('status_surat', ['diterbitkan', 'laporan_revisi_bku']) // Asumsi status-status ini
            ->count();


        // --- Mengambil Daftar Tugas untuk Tabel Detail ---
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $daftarTugas = (clone $query)
            ->when($search, function ($q, $search) {
                return $q->where('perihal_tugas', 'like', "%{$search}%");
            })
            ->orderBy('tanggal_berangkat', 'desc')
            ->paginate($perPage)
            ->appends(request()->query());


        return view('layouts.pelaksana.dashboard', array_merge(
            compact('totalPenugasan', 'penugasanBaru', 'laporanBelumSelesai', 'daftarTugas', 'search'),
            $this->getGlobalViewData()
        ));
    }
    
    /**
     * Menampilkan halaman upload bukti perjalanan.
     */
    public function bukti()
    {
        return view('layouts.pelaksana.bukti', $this->getGlobalViewData());
    }

    /**
     * Menampilkan halaman upload laporan akhir.
     */
    public function laporan()
    {
        return view('layouts.pelaksana.laporan', $this->getGlobalViewData());
    }

    /**
     * Menampilkan halaman dokumen surat tugas.
     */
    public function dokumen()
    {
        return view('layouts.pelaksana.dokumen', $this->getGlobalViewData());
    }

    /**
     * Menampilkan halaman status laporan.
     */
    public function statusLaporan()
    {
        return view('layouts.pelaksana.statusLaporan', $this->getGlobalViewData());
    }
}