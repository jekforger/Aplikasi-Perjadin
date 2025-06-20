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
        $dalamProsesBku = 0; // Placeholder, implementasikan nanti
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
                                                        ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%")
                                                        ->orWhere('nomor_surat_tugas_resmi', 'like', "%{$search}%");
                                        })
                                        ->orderBy('updated_at', 'desc')
                                        ->paginate($perPage)
                                        ->appends(['search' => $search, 'per_page' => $perPage]);

        $roleDisplayName = $direkturDisplayName;
        $userRole = Auth::user()->role;

        return view('layouts.direktur.dashboard', compact('dashboardStats', 'pengusulanDetails', 'roleDisplayName', 'userRole'));
    }
}