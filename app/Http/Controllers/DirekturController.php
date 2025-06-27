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
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class DirekturController extends Controller
{
    protected $loginController;

    public function __construct(LoginController $loginController)
    {
        $this->loginController = $loginController;
    }

    private function getLoggedInDirekturDisplayName()
    {
        if (Auth::check() && Auth::user()->role === 'direktur') {
            return $this->loginController->getRoleDisplayName('direktur');
        }
        return null;
    }

    public function dashboard(Request $request)
    {
        $direkturDisplayName = $this->getLoggedInDirekturDisplayName();

        if (is_null($direkturDisplayName)) {
            Log::warning("Akses Direktur Dashboard oleh non-Direktur role: " . (Auth::check() ? Auth::user()->role : 'Guest'));
            abort(403, 'Akses Ditolak: Anda bukan Direktur yang valid.');
        }

        $totalPengusulan = SuratTugas::count();
        $dalamProsesWadir = SuratTugas::whereIn('status_surat', ['pending_wadir_review', 'reverted_by_wadir'])->count();
        $dalamProsesBku = 0; // Placeholder
        $bertugas = SuratTugas::where('status_surat', 'diterbitkan')
                                ->whereDate('tanggal_berangkat', '<=', Carbon::today())
                                ->whereDate('tanggal_kembali', '>=', Carbon::today())
                                ->count();
        
        $dashboardStats = [
            'total_pengusulan' => $totalPengusulan,
            'dalam_proses_wadir' => $dalamProsesWadir,
            'dalam_proses_bku' => $dalamProsesBku,
            'bertugas' => $bertugas,
        ];

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $pengusulanDetails = SuratTugas::whereIn('status_surat', [
                                            'approved_by_wadir',
                                            'rejected_by_direktur',
                                            'reverted_by_direktur',
                                            'diterbitkan'
                                        ])
                                        ->when($search, function ($query) use ($search) {
                                            return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                        ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                        })
                                        ->orderBy('updated_at', 'desc')
                                        ->paginate($perPage)
                                        ->appends(['search' => $search, 'per_page' => $perPage]);

        $roleDisplayName = $direkturDisplayName;
        $userRole = Auth::user()->role;

        return view('layouts.direktur.dashboard', compact('dashboardStats', 'pengusulanDetails', 'roleDisplayName', 'userRole'));
    }

    public function persetujuan(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $suratTugasUntukReview = SuratTugas::where('status_surat', 'approved_by_wadir')
                                        ->with(['pengusul', 'wadirApprover'])
                                        ->when($search, function ($query) use ($search) {
                                            return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                         ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                        })
                                        ->orderBy('tanggal_paraf_wadir', 'desc')
                                        ->paginate($perPage)
                                        ->appends(['search' => $search, 'per_page' => $perPage]);
        
        $roleDisplayName = $this->getLoggedInDirekturDisplayName();
        $userRole = Auth::user()->role;

        return view('layouts.direktur.persetujuan', compact('suratTugasUntukReview', 'search', 'roleDisplayName', 'userRole'));
    }

    public function paraf()
    {
        $user = Auth::user();
        $currentSignaturePath = $user->signature_file_path ?? null;

        $roleDisplayName = $this->getLoggedInDirekturDisplayName();
        $userRole = Auth::user()->role;

        return view('layouts.direktur.paraf', compact('currentSignaturePath', 'roleDisplayName', 'userRole'));
    }

    public function uploadParaf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'signature_file' => 'required|file|mimes:png|max:1024',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        if ($user->signature_file_path && Storage::disk('public')->exists($user->signature_file_path)) {
            Storage::disk('public')->delete($user->signature_file_path);
        }

        $filePath = $request->file('signature_file')->store('signatures/direktur', 'public');
        $user->signature_file_path = $filePath;
        $user->save();

        return redirect()->route('direktur.paraf')->with('success_message', 'Tanda tangan berhasil diunggah.');
    }

    public function reviewSuratTugas($id)
    {
        $suratTugas = SuratTugas::with(['pengusul', 'wadirApprover', 'detailPelaksanaTugas.personable'])
                                ->findOrFail($id);

        if (!in_array($suratTugas->status_surat, ['approved_by_wadir', 'reverted_by_direktur'])) {
            return redirect()->route('direktur.persetujuan')->with('error', 'Surat tugas ini tidak lagi dalam tahap persetujuan Anda.');
        }

        $roleDisplayName = $this->getLoggedInDirekturDisplayName();
        $userRole = Auth::user()->role;

        return view('layouts.direktur.reviewSuratTugas', compact('suratTugas', 'roleDisplayName', 'userRole'));
    }

    public function processSuratTugasReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|string|in:approve,reject,revert',
            'catatan_revisi' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $suratTugas = SuratTugas::findOrFail($id);
        $userDirektur = Auth::user();

        \DB::beginTransaction();

        try {
            $action = $request->input('action');
            $catatanRevisi = $request->input('catatan_revisi');
            $message = '';

            switch ($action) {
                case 'approve':
                    $suratTugas->status_surat = 'diterbitkan';
                    $suratTugas->tanggal_persetujuan_direktur = Carbon::now();
                    $suratTugas->direktur_approver_id = $userDirektur->id;
                    $suratTugas->nomor_surat_tugas_resmi = $suratTugas->surat_tugas_id . '/ST-DIR/' . Carbon::now()->format('m/Y');

                    $tandaTanganDirekturPath = $userDirektur->signature_file_path ?? null;
                    $pdf = Pdf::loadView('pdf.surat_tugas_template', [
                        'suratTugas' => $suratTugas->fresh(),
                        'tandaTanganDirekturPath' => $tandaTanganDirekturPath,
                    ]);
                    $pdfFileName = 'surat-tugas-resmi-' . $suratTugas->surat_tugas_id . '.pdf';
                    $pdfPath = 'surat_tugas_final/' . $pdfFileName;
                    Storage::disk('public')->put($pdfPath, $pdf->output());

                    $suratTugas->path_file_surat_tugas_final = $pdfPath;
                    $message = 'Surat tugas berhasil disetujui dan diterbitkan.';
                    break;

                case 'reject':
                    $suratTugas->status_surat = 'rejected_by_direktur';
                    $suratTugas->catatan_revisi = $catatanRevisi;
                    $message = 'Surat tugas berhasil ditolak.';
                    break;

                case 'revert':
                    $suratTugas->status_surat = 'reverted_by_direktur';
                    $suratTugas->catatan_revisi = $catatanRevisi;
                    $message = 'Surat tugas berhasil dikembalikan untuk revisi.';
                    break;
            }

            $suratTugas->save();
            \DB::commit();

            Log::info("Direktur (ID: {$userDirektur->id}) memproses Surat Tugas ID {$id} dengan aksi '{$action}'.");
            
            // TODO: Kirim notifikasi email ke Pelaksana Tugas

            return redirect()->route('direktur.persetujuan')->with('success_message', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error memproses review Direktur: '.$e->getMessage(), ['id' => $id, 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}