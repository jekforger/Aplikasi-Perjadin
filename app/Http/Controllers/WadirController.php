<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Auth\LoginController;
use App\Models\SuratTugas;
use App\Models\User;
use Carbon\Carbon;

class WadirController extends Controller
{
    protected $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    private function getLoggedInWadirDisplayName()
    {
        if (Auth::check() && Auth::user()->role) {
            $userRole = Auth::user()->role;
            if (str_starts_with($userRole, 'wadir_')) {
                return $this->loginController->getRoleDisplayName($userRole);
            }
        }
        return null;
    }

    public function dashboard(Request $request)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();

        if (is_null($wadirDisplayName)) {
            Log::warning("Akses Wadir Dashboard oleh non-Wadir role: " . (Auth::check() ? Auth::user()->role : 'Guest'));
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid untuk mengakses dashboard ini.');
        }

        // --- Statistik Dashboard ---
        $totalPengusulanWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)->count();
        $usulanBaruWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                    ->where('status_surat', 'pending_wadir_review')->count();
        $dalamProsesDirekturWadir = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                            ->where('status_surat', 'approved_by_wadir')->count();
        $bertugas = SuratTugas::where('status_surat', 'diterbitkan')
                                ->whereDate('tanggal_berangkat', '<=', Carbon::today())
                                ->whereDate('tanggal_kembali', '>=', Carbon::today())
                                ->count();
        $dikembalikanDitolak = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                        ->whereIn('status_surat', ['reverted_by_wadir', 'rejected_by_wadir', 'reverted_by_direktur', 'rejected_by_direktur'])->count();

        $dashboardStats = [
            'total_pengusulan' => $totalPengusulanWadir,
            'usulan_baru' => $usulanBaruWadir,
            'dalam_proses_direktur' => $dalamProsesDirekturWadir,
            'bertugas' => $bertugas,
            'dikembalikan_ditolak' => $dikembalikanDitolak,
        ];

        // --- Data untuk Tabel "Detail" (Bawah dashboard) ---
        $search = $request->input('search_all');
        $perPage = $request->input('per_page', 10);

        $allSuratTugas = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                ->when($search, function ($query) use ($search) {
                                    return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                })
                                ->orderBy('created_at', 'desc')
                                ->paginate($perPage)
                                ->appends(['search_all' => $search, 'per_page' => $perPage]);

        $roleDisplayName = $wadirDisplayName;

        // Pada dashboard, tabel untuk review terpisah akan dihapus, jadi hanya perlu allSuratTugas
        return view('layouts.Wadir.dashboard', compact('dashboardStats', 'allSuratTugas', 'roleDisplayName', 'search'));
    }

    /**
     * Menampilkan halaman daftar pengajuan yang menunggu persetujuan Wadir.
     * Halaman ini sekarang juga akan menampilkan daftar yang sudah diparaf.
     * @param Request $request Untuk filter dan paginasi
     */
    public function persetujuan(Request $request)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();
        if (is_null($wadirDisplayName)) {
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.');
        }

        $filterStatus = $request->input('status', 'pending'); // Filter default: pending
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = SuratTugas::where('diusulkan_kepada', $wadirDisplayName)
                                ->with(['pengusul', 'detailPelaksanaTugas.personable'])
                                ->when($search, function ($q) use ($search) {
                                    return $q->where('perihal_tugas', 'like', "%{$search}%")
                                             ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%")
                                             ->orWhere('nomor_surat_tugas_resmi', 'like', "%{$search}%");
                                });

        switch ($filterStatus) {
            case 'pending':
                $query->whereIn('status_surat', ['pending_wadir_review', 'reverted_by_wadir', 'reverted_by_direktur']);
                break;
            case 'paraf':
                $query->whereIn('status_surat', ['approved_by_wadir', 'approved_by_direktur', 'diterbitkan']);
                break;
            case 'ditolak':
                $query->whereIn('status_surat', ['rejected_by_wadir', 'rejected_by_direktur']);
                break;
            default: // all
                // Tidak perlu filter status tambahan, ambil semua yang terkait dengan Wadir ini
                break;
        }

        $suratTugasList = $query->orderBy('updated_at', 'desc')
                                ->paginate($perPage)
                                ->appends(['search' => $search, 'per_page' => $perPage, 'status' => $filterStatus]);

        return view('layouts.Wadir.persetujuan', compact('suratTugasList', 'search', 'filterStatus'));
    }

    /**
     * Metode ini sekarang akan menjadi placeholder atau diarahkan ke persetujuan.
     */
    public function paraf(Request $request)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();
        if (is_null($wadirDisplayName)) {
            abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.');
        }

        // Ambil user yang sedang login
        $user = Auth::user();
        // Cek apakah user sudah punya file paraf
        $currentParafPath = $user->para_file_path; // Asumsi ada kolom 'para_file_path' di tabel users

        return view('layouts.Wadir.uploadParaf', compact('currentParafPath')); // Ganti nama view menjadi uploadParaf
    }

    /**
     * Memproses upload file paraf digital untuk Wadir.
     * Ini memerlukan kolom 'para_file_path' di tabel 'users'.
     * @param Request $request
     */
    public function uploadParaf(Request $request)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();
        if (is_null($wadirDisplayName)) {
            return response()->json(['success' => false, 'message' => 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.'], 403);
        }

        $user = Auth::user();

        try {
            // Jika ada input base64 dari signature pad
            if ($request->has('signature')) {
                $data = $request->input('signature');
                $image = str_replace('data:image/png;base64,', '', $data);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                $fileName = 'paraf_wadir/' . uniqid() . '.png';
                Storage::disk('public')->put($fileName, $imageData);

                // Hapus file lama
                if ($user->para_file_path) {
                    Storage::disk('public')->delete($user->para_file_path);
                }

                $user->para_file_path = $fileName;
                $user->save();

                return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan.']);
            }

            // Kalau tidak, fallback ke upload file manual
            $validator = Validator::make($request->all(), [
                'paraf_file' => 'required|file|mimes:pdf,png,jpg,jpeg|max:1024',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
            }

            if ($user->para_file_path) {
                Storage::disk('public')->delete($user->para_file_path);
            }

            $filePath = $request->file('paraf_file')->store('paraf_wadir', 'public');
            $user->para_file_path = $filePath;
            $user->save();

            return response()->json(['success' => true, 'message' => 'File paraf berhasil diunggah.', 'filePath' => Storage::url($filePath)]);
        } catch (\Exception $e) {
            Log::error('Error unggah paraf: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.']);
        }
    }


    public function deleteParaf(Request $request)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();
        if (is_null($wadirDisplayName)) {
            return response()->json(['success' => false, 'message' => 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.'], 403);
        }

        $user = Auth::user();

        try {
            if ($user->para_file_path && Storage::disk('public')->exists($user->para_file_path)) {
                Storage::disk('public')->delete($user->para_file_path);
                $user->para_file_path = null; // Set path menjadi null di database
                $user->save();
                Log::info("Wadir (ID: {$user->id}, Role: {$user->role}) berhasil menghapus file paraf.");
                return response()->json(['success' => true, 'message' => 'File paraf berhasil dihapus.']);
            }

            return response()->json(['success' => false, 'message' => 'Tidak ada file paraf untuk dihapus.'], 404);

        } catch (\Exception $e) {
            Log::error('Error menghapus file paraf oleh Wadir: '.$e->getMessage(), [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus file: ' . $e->getMessage()], 500);
        }
    }


    public function reviewSuratTugas($id)
{
    $wadirDisplayName = $this->getLoggedInWadirDisplayName();
    if (is_null($wadirDisplayName)) { abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.'); }

    // Tambahkan with('wadirApprover') agar relasi para_file_path bisa diakses di view
    $suratTugas = SuratTugas::where('surat_tugas_id', $id)
                            ->where('diusulkan_kepada', $wadirDisplayName)
                            ->with(['pengusul', 'detailPelaksanaTugas.personable', 'wadirApprover']) // <-- TAMBAHKAN wadirApprover
                            ->firstOrFail();

    return view('layouts.Wadir.reviewSuratTugas', compact('suratTugas'));
}


        public function processSuratTugasReview(Request $request, $id)
    {
        $wadirDisplayName = $this->getLoggedInWadirDisplayName();
        if (is_null($wadirDisplayName)) { abort(403, 'Akses Ditolak: Anda bukan Wakil Direktur yang valid.'); }

        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,reject,revert',
            'catatan_revisi' => 'nullable|string|max:1000',
            'updated_sumber_dana' => 'nullable|string|in:RM,PNBP,Polban,Penyelenggara,Polban dan Penyelenggara', // Validasi untuk sumber dana baru
            'signature_data' => 'nullable|string', // Validasi untuk data signature
            'signature_position' => 'nullable|string', // Validasi untuk posisi signature
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $suratTugas = SuratTugas::where('surat_tugas_id', $id)
                                ->where('diusulkan_kepada', $wadirDisplayName)
                                ->firstOrFail();

        \DB::beginTransaction();

        try {
            $action = $request->input('action');
            $catatanRevisi = $request->input('catatan_revisi');
            $updatedSumberDana = $request->input('updated_sumber_dana'); // Ambil sumber dana yang diupdate
            $signatureData = $request->input('signature_data'); // Ambil data signature
            $signaturePosition = $request->input('signature_position'); // Ambil posisi signature

            // Jika sumber dana diupdate dari modal, terapkan perubahannya
            if ($updatedSumberDana) {
                $suratTugas->sumber_dana = $updatedSumberDana;
            }

            switch ($action) {
                case 'approve':
                    $suratTugas->status_surat = 'approved_by_wadir';
                    $suratTugas->tanggal_paraf_wadir = Carbon::now();
                    $suratTugas->wadir_approver_id = Auth::id();
                    
                    // Simpan signature data dan posisi jika ada
                    if ($signatureData) {
                        $suratTugas->wadir_signature_data = $signatureData;
                    }
                    if ($signaturePosition) {
                        $suratTugas->wadir_signature_position = json_decode($signaturePosition, true);
                    }
                    
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
            }

            $suratTugas->save();
            \DB::commit();

            Log::info("Wadir (ID: ".Auth::id().") memproses Surat Tugas ID {$id} dengan aksi '{$action}'. Status baru: {$suratTugas->status_surat}. Sumber dana baru: {$updatedSumberDana}");

            return redirect()->route('wadir.persetujuan')->with('success_message', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error memproses review Surat Tugas oleh Wadir: '.$e->getMessage(), [
                'surat_tugas_id' => $id, 'user_id' => Auth::id(), 'request_data' => $request->all(), 'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'Terjadi kesalahan saat memproses keputusan: ' . $e->getMessage())->withInput();
        }
    }
}