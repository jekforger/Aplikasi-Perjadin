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

class PelaksanaController extends Controller
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

    /**
     * Dashboard pelaksana.
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai_id;

        if ($pegawaiId) {
            $query = SuratTugas::whereHas('detailPelaksanaTugas', function ($q) use ($pegawaiId) {
                $q->where('personable_id', $pegawaiId)
                  ->where('personable_type', Pegawai::class);
            });
        } else {
            $query = SuratTugas::where('surat_tugas_id', -1);
        }

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
            ->when($search, fn ($q, $search) =>
                $q->where('perihal_tugas', 'like', "%{$search}%"))
            ->orderBy('tanggal_berangkat', 'desc')
            ->paginate($perPage)
            ->appends(request()->query());

        return view('layouts.pelaksana.dashboard', array_merge(
            compact('totalPenugasan', 'penugasanBaru', 'laporanBelumSelesai', 'daftarTugas', 'search'),
            $this->getGlobalViewData()
        ));
    }

    /**
     * Halaman upload bukti perjalanan.
     */
    public function bukti(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai_id;

        if ($pegawaiId) {
            $query = SuratTugas::whereHas('detailPelaksanaTugas', function ($q) use ($pegawaiId) {
                $q->where('personable_id', $pegawaiId)
                  ->where('personable_type', Pegawai::class);
            });
        } else {
            $query = SuratTugas::where('surat_tugas_id', -1);
        }

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $daftarTugas = (clone $query)
            ->when($search, fn ($q, $search) =>
                $q->where('perihal_tugas', 'like', "%{$search}%"))
            ->orderBy('tanggal_berangkat', 'desc')
            ->paginate($perPage)
            ->appends(request()->query());

        return view('layouts.pelaksana.bukti', array_merge(
            compact('daftarTugas', 'search'),
            $this->getGlobalViewData()
        ));
    }

    /**
     * Halaman upload laporan akhir.
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();
        $pegawaiId = $user->pegawai_id;

        if ($pegawaiId) {
            $query = SuratTugas::whereHas('detailPelaksanaTugas', function ($q) use ($pegawaiId) {
                $q->where('personable_id', $pegawaiId)
                  ->where('personable_type', Pegawai::class);
            });
        } else {
            $query = SuratTugas::where('surat_tugas_id', -1);
        }

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $daftarTugas = (clone $query)
            ->when($search, fn ($q, $search) =>
                $q->where('perihal_tugas', 'like', "%{$search}%"))
            ->orderBy('tanggal_berangkat', 'desc')
            ->paginate($perPage)
            ->appends(request()->query());

        return view('layouts.pelaksana.laporan', array_merge(
            compact('daftarTugas', 'search'),
            $this->getGlobalViewData()
        ));
    }

    /**
     * Halaman dokumen surat tugas.
     */
    public function dokumen()
    {
        return view('layouts.pelaksana.dokumen', $this->getGlobalViewData());
    }

    /**
     * Halaman status laporan.
     */
    public function statusLaporan()
    {
        return view('layouts.pelaksana.statusLaporan', $this->getGlobalViewData());
    }

    /**
     * Lihat bukti file.
     */

     
     public function lihatBukti($surat_tugas_id)
    {
        $suratTugas = SuratTugas::with('laporanPerjalananDinas.dokumenLampiran')
                        ->findOrFail($surat_tugas_id);

        $laporan = $suratTugas->laporanPerjalananDinas;
        $lampiranList = $laporan ? $laporan->dokumenLampiran : collect();

        return view('.layouts.pelaksana.lihatBukti', compact('suratTugas', 'lampiranList', 'laporan'));
    }

     

    /**
     * Upload lampiran bukti.
     */
    public function buatLaporan(Request $request)
    {
        $request->validate([
            'surat_tugas_id' => 'required|exists:surat_tugas,surat_tugas_id',
        ]);

        // Buat laporan baru
        $laporan = new LaporanPerjalananDinas();
        $laporan->surat_tugas_id = $request->surat_tugas_id;
        $laporan->user_id = auth()->id(); // sesuaikan
        $laporan->tanggal_pengumpulan_laporan = now();
        $laporan->save();

        // Redirect ke halaman upload bukti, 
        // dengan optional parameter untuk buka modal spesifik
        return redirect()
            ->route('pelaksana.bukti', ['surat_tugas_id' => $request->surat_tugas_id])
            ->with('success', 'Laporan berhasil dibuat. Silakan upload bukti perjalanan.');
    }
        /**
         * Tampilkan detail lampiran.
         */
        public function showLampiran($laporan_id, $lampiran_id)
        {
            $laporan = LaporanPerjalananDinas::findOrFail($laporan_id);
            $lampiran = $laporan->dokumenLampiran()->findOrFail($lampiran_id);

            return view('layouts.pelaksana.showLampiran', array_merge(
                compact('laporan', 'lampiran'),
                $this->getGlobalViewData()
            ));
        }

        public function uploadLampiran(Request $request)
        {
            $request->validate([
                'surat_tugas_id' => 'required|exists:surat_tugas,surat_tugas_id',
                'jenis_dokumen' => 'required|string',
                'file.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            // Buat laporan jika belum ada
            $laporan = LaporanPerjalananDinas::firstOrCreate(
                ['surat_tugas_id' => $request->surat_tugas_id],
                [
                    'user_id' => auth()->id(),
                    'tanggal_pengumpulan_laporan' => now(),
                ]
            );

            foreach ($request->file('file') as $file) {
                $path = $file->store('bukti_perjalanan', 'public');

                DokumenLampiranLaporan::create([
                    'laporan_id' => $laporan->laporan_id,
                    'jenis_dokumen' => $request->jenis_dokumen,
                    'path_file' => $path,
                    'nama_file' => $file->getClientOriginalName(), // ini WAJIB
                    'tanggal_unggah' => now(), // INI WAJIB
                ]);
            }

            return redirect()->back()->with('success', 'Laporan & bukti berhasil diunggah!');
        }
        


    /**
     * Form edit lampiran.
     */
    public function editLampiran($laporan_id)
    {
        $laporan = LaporanPerjalananDinas::findOrFail($laporan_id);

        return view('layouts.pelaksana.editLampiran', array_merge(
            compact('laporan', 'lampiran'),
            $this->getGlobalViewData()
        ));
    }

    /**
     * Update lampiran.
     */
    public function updateLampiran(Request $request, $laporan_id, $lampiran_id)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $laporan = LaporanPerjalananDinas::findOrFail($laporan_id);
        $lampiran = $laporan->dokumenLampiran()->findOrFail($lampiran_id);

        $lampiran->jenis_dokumen = $request->jenis_dokumen;

        if ($request->hasFile('file')) {
            if ($lampiran->path_file) {
                Storage::disk('public')->delete($lampiran->path_file);
            }
            $lampiran->path_file = $request->file('file')->store('dokumen_lampiran', 'public');
        }

        $lampiran->save();

        return redirect()->back()->with('success', 'Lampiran berhasil diperbarui.');
    }

    /**
     * Hapus lampiran.
     */
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


}
