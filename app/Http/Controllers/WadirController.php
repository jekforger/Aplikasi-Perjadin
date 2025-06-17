<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\LoginController;
use App\Models\SuratTugas;
use App\Models\User;
use Carbon\Carbon;

class WadirController extends Controller
{
    // Instance LoginController untuk mengakses helper roles
    protected $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    /**
     * Helper untuk mendapatkan display name Wadir dari role key.
     * Contoh: 'wadir_1' -> 'Wakil Direktur I'
     */
    private function getLoggedInWadirDisplayName()
    {
        $userRole = Auth::user()->role;
        // Hanya proses jika role adalah salah satu Wadir
        if (str_starts_with($userRole, 'wadir_')) {
            return $this->loginController->getRoleDisplayName($userRole);
        }
        return null; // Bukan role Wadir spesifik
    }

    /**
     * Menampilkan halaman dashboard untuk Wakil Direktur.
     */
    public function dashboard()
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();

        if (is_null($wadirDisplayName)) {
            // Ini bisa jadi error atau redirect ke halaman unauthorized
            // Untuk saat ini, kita bisa log dan tampilkan pesan error
            Log::warning("Akses Wadir Dashboard oleh non-Wadir role: " . (Auth::check() ? Auth::user()->role : 'Guest'));
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.');
        }

        // Ambil data surat tugas yang perlu direview oleh Wadir yang sedang login
        $suratTugasQuery = SuratTugas::where('diusulkan_kepada', $wadirDisplayName) // FILTER UTAMA
                                        ->whereIn('status_surat', ['pending_wadir_review', 'reverted_by_wadir', 'reverted_by_direktur'])
                                        ->with(['pengusul', 'detailPelaksanaTugas.personable'])
                                        ->orderBy('created_at', 'desc');

        $suratTugasUntukReview = $suratTugasQuery->paginate(10);

        // Ambil statistik nyata dari database (difilter untuk Wadir yang login)
        $totalPengusulanWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)->count();
        $usulanBaruWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                    ->where('status_surat', 'pending_wadir_review')->count();
        $dalamProsesDirekturWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                            ->where('status_surat', 'approved_by_wadir')->count();
        $dikembalikanWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                        ->whereIn('status_surat', ['reverted_by_wadir', 'rejected_by_wadir', 'reverted_by_direktur', 'rejected_by_direktur'])->count();

        // Statistik 'bertugas' mungkin tidak spesifik Wadir, tapi pengusulan secara umum
        // Jika perlu spesifik Wadir, perlu kolom tambahan di surat_tugas atau melalui relasi.
        $sedangBertugas = SuratTugas::where('status_surat', 'diterbitkan')
                                    ->whereDate('tanggal_berangkat', '<=', Carbon::today())
                                    ->whereDate('tanggal_kembali', '>=', Carbon::today())
                                    ->count();


        $dashboardStats = [
            'total_pengusulan' => $totalPengusulanWadir,
            'usulan_baru' => $usulanBaruWadir,
            'dalam_proses_direktur' => $dalamProsesDirekturWadir,
            'bertugas' => $sedangBertugas, // Ini masih global, perlu disesuaikan jika ingin hanya yang diusulkan ke Wadir ini
            'dikembalikan' => $dikembalikanWadir,
        ];

        $roleDisplayName = $wadirDisplayName; // Sudah didapatkan di awal fungsi

        // Teruskan data surat tugas dan statistik ke view
        return view('layouts.Wadir.dashboard', compact('dashboardStats', 'suratTugasUntukReview', 'roleDisplayName'));
    }

    /**
     * Menampilkan halaman detail untuk review Surat Tugas oleh Wakil Direktur.
     */
    public function reviewSuratTugas($id)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();

        if (is_null($wadirDisplayName)) {
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.');
        }

        // Temukan Surat Tugas berdasarkan ID DAN diusulkan kepada Wadir yang login
        $suratTugas = SuratTugas::where('surat_tugas_id', $id)
                                ->where('diusulkan_kepada', $wadirDisplayName) // FILTER UTAMA
                                ->with(['pengusul', 'detailPelaksanaTugas.personable'])
                                ->firstOrFail(); // Gunakan firstOrFail untuk 404 jika tidak ditemukan atau tidak sesuai Wadir

        return view('layouts.Wadir.reviewSuratTugas', compact('suratTugas'));
    }

    /**
     * Memproses keputusan Wakil Direktur terhadap Surat Tugas.
     */
    public function processSuratTugasReview(Request $request, $id)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();

        if (is_null($wadirDisplayName)) {
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,reject,revert',
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Temukan Surat Tugas berdasarkan ID DAN diusulkan kepada Wadir yang login
        $suratTugas = SuratTugas::where('surat_tugas_id', $id)
                                ->where('diusulkan_kepada', $wadirDisplayName) // FILTER UTAMA
                                ->firstOrFail();

        \DB::beginTransaction();

        try {
            $action = $request->input('action');
            $catatanRevisi = $request->input('catatan_revisi');

            switch ($action) {
                case 'approve':
                    $suratTugas->status_surat = 'approved_by_wadir';
                    $suratTugas->tanggal_paraf_wadir = Carbon::now();
                    $suratTugas->wadir_approver_id = Auth::id();
                    $message = 'Surat tugas berhasil disetujui dan diteruskan ke Direktur.';
                    break;
                case 'reject':
                    $suratTugas->status_surat = 'rejected_by_wadir';
                    $suratTugas->catatan_revisi = $catatanRevisi;
                    $suratTugas->wadir_approver_id = Auth::id();
                    $message = 'Surat tugas berhasil ditolak.';
                    break;
                case 'revert':
                    $suratTugas->status_surat = 'reverted_by_wadir';
                    $suratTugas->catatan_revisi = $catatanRevisi;
                    $suratTugas->wadir_approver_id = Auth::id();
                    $message = 'Surat tugas berhasil dikembalikan untuk revisi.';
                    break;
                default:
                    throw new \Exception('Aksi tidak valid.');
            }

            $suratTugas->save();

            \DB::commit();

            Log::info("Wadir (ID: ".Auth::id().", Role: ".Auth::user()->role.") memproses Surat Tugas ID {$id} dengan aksi '{$action}'. Status baru: {$suratTugas->status_surat}");

            // TODO: Kirim notifikasi email/in-app sesuai alur
            // Jika disetujui -> ke Direktur
            // Jika ditolak/revisi -> ke Pengusul

            return redirect()->route('wadir.dashboard')->with('success_message', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error memproses review Surat Tugas oleh Wadir: '.$e->getMessage(), [
                'surat_tugas_id' => $id,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role,
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses keputusan: ' . $e->getMessage())->withInput();
        }
    }
}