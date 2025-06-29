<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SuratTugas;
use App\Models\Pegawai;
use App\Models\LaporanPerjalananDinas;
use App\Models\DokumenLampiranLaporan;
use App\Http\Controllers\Auth\LoginController;
use Carbon\Carbon;

class BkuController extends Controller
{
    /**
     * Helper untuk meneruskan variabel global ke layout.
     */
    private function getGlobalViewData()
    {
        $userRole = Auth::user()->role;
        $roleDisplayName = app(LoginController::class)->getRoleDisplayName($userRole);
        return compact('userRole', 'roleDisplayName');
    }
    
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai_id;

        // Ambil semua surat tugas yang relevan
        $query = SuratTugas::with(['laporanPerjalananDinas'])
            ->when($pegawaiId, function ($q) use ($pegawaiId) {
                $q->whereHas('detailPelaksanaTugas', function ($sub) use ($pegawaiId) {
                    $sub->where('personable_id', $pegawaiId)
                        ->where('personable_type', Pegawai::class);
                });
            });

        $totalPenugasan = (clone $query)->count();
        $penugasanBaru = (clone $query)
            ->where('status_surat', 'diterbitkan')
            ->whereDate('tanggal_kembali', '>=', Carbon::today())
            ->count();
        $laporanBelumSelesai = (clone $query)
            ->whereIn('status_surat', ['diterbitkan', 'laporan_revisi_bku'])
            ->count();

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $daftarTugas = (clone $query)
            ->when($search, fn ($q) =>
                $q->where('perihal_tugas', 'like', "%{$search}%"))
            ->orderBy('tanggal_berangkat', 'desc')
            ->paginate($perPage)
            ->appends(request()->query());

        return view('layouts.bku.dashboard', array_merge(
            compact('totalPenugasan', 'penugasanBaru', 'laporanBelumSelesai', 'daftarTugas', 'search'),
            $this->getGlobalViewData()
        ));
    }


    public function laporan(Request $request)
    {
        $query = SuratTugas::whereHas('laporanPerjalananDinas.dokumenLampiran');

        if ($request->filled('search')) {
            $query->where('perihal_tugas', 'like', '%' . $request->search . '%');
        }

        $daftarTugas = $query->latest()->paginate(10)->appends($request->query());

        return view('layouts.bku.laporan', compact('daftarTugas'));
    }

    public function lihatLaporan($surat_tugas_id)
    {
        $suratTugas = SuratTugas::with('laporanPerjalananDinas.dokumenLampiran')
                        ->findOrFail($surat_tugas_id);

        $laporan = $suratTugas->laporanPerjalananDinas;
        $lampiranList = $laporan ? $laporan->dokumenLampiran : collect();

        return view('.layouts.bku.lihatLaporan', compact('suratTugas', 'lampiranList', 'laporan'));
    }

    public function destroyLampiran($laporan_id, $lampiran_id)
    {
        $lampiran = DokumenLampiranLaporan::where('laporan_id', $laporan_id)
                                ->where('dokumen_lampiran_id', $lampiran_id)
                                ->firstOrFail();

        // Hapus file dari storage
        if (Storage::exists($lampiran->path_file)) {
            Storage::delete($lampiran->path_file);
        }

        $lampiran->delete();

        return back()->with('success', 'Lampiran berhasil dihapus.');
    }

    public function terimaLaporan($id)
    {
        // Ambil data laporan berdasarkan surat_tugas_id
        $laporan = LaporanPerjalananDinas::where('surat_tugas_id', $id)->firstOrFail();

        // Update status laporan jadi 'Diterima' dan kosongkan catatan
        $laporan->status_laporan = 'Diterima';
        $laporan->catatan_verifikasi_bku = null;
        $laporan->save();

        return redirect()->route('bku.laporan')
            ->with('success', 'Laporan berhasil diterima.');
    }


    public function kembalikanLaporan(Request $request, $id)
    {
        try {
            $request->validate([
                'catatan_verifikasi_bku' => 'required|string',
            ]);

            $laporan = LaporanPerjalananDinas::where('surat_tugas_id', $id)->firstOrFail();

            $laporan->status_laporan = 'dikembalikan';
            $laporan->catatan_verifikasi_bku = $request->catatan_verifikasi_bku;
            $laporan->save();

            return redirect()->route('bku.laporan')->with('success', 'Laporan berhasil dikembalikan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['msg' => $e->getMessage()]);
        }
    }



}
