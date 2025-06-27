<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Models\SuratTugas;
use App\Models\DetailPelaksanaTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage; // Untuk menghapus file draft

class PengusulController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Pengusul.
     */
    public function dashboard()
    {
        $userId = Auth::id(); // Dapatkan ID user pengusul yang login

        // Ambil statistik nyata dari database untuk pengusul yang login
        $totalUsulan = SuratTugas::where('user_id', $userId)->count();
        $laporanSelesai = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'laporan_selesai')->count(); // Perlu status 'laporan_selesai'
        $laporanBelumSelesai = SuratTugas::where('user_id', $userId)
                                         ->where('status_surat', 'approved_by_direktur')->count(); // Atau status lain sebelum laporan selesai
        $sedangBertugas = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'diterbitkan')
                                    ->whereDate('tanggal_berangkat', '<=', Carbon::today())
                                    ->whereDate('tanggal_kembali', '>=', Carbon::today())
                                    ->count();
        $dikembalikan = SuratTugas::where('user_id', $userId)
                                  ->whereIn('status_surat', ['rejected_by_wadir', 'reverted_by_wadir', 'rejected_by_direktur', 'reverted_by_direktur'])->count();

        // Data untuk tabel ringkasan di dashboard (misal: 5 pengajuan terbaru)
        $latestPengusulan = SuratTugas::where('user_id', $userId)
                                      ->orderBy('created_at', 'desc')
                                      ->take(5) // Ambil 5 terbaru
                                      ->get();


        return view('layouts.pengusul.dashboard', compact(
            'totalUsulan', 'laporanSelesai', 'laporanBelumSelesai', 'sedangBertugas', 'dikembalikan', 'latestPengusulan'
        ));
    }

    public function pilih(Request $request)
    {
        // ... (metode ini tetap sama) ...
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = Pegawai::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
        }

        $pegawais = $query->paginate($perPage)->withQueryString();

        return view('layouts.pengusul.dataPengusul', compact('pegawais'));
    }

    /**
     * Menampilkan halaman status pengusulan untuk Pengusul.
     * @param Request $request Untuk filter dan paginasi
     */
    public function status(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $statusPengusulan = SuratTugas::where('user_id', $userId)
                                    ->when($search, function ($query) use ($search) {
                                        return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                     ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage)
                                    ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('layouts.pengusul.status', compact('statusPengusulan', 'search'));
    }

    /**
     * Menampilkan halaman draft pengusulan untuk Pengusul.
     * @param Request $request Untuk filter dan paginasi
     */
    public function draft(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $draftPengusulan = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'draft') // Hanya draft
                                    ->when($search, function ($query) use ($search) {
                                        return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                     ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage)
                                    ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('layouts.pengusul.draft', compact('draftPengusulan', 'search'));
    }

    /**
     * Menghapus draft pengusulan.
     * @param int $id ID Surat Tugas
     */
    public function deleteDraft($id)
    {
        $userId = Auth::id();
        $draft = SuratTugas::where('surat_tugas_id', $id)
                           ->where('user_id', $userId)
                           ->where('status_surat', 'draft')
                           ->firstOrFail();

        \DB::beginTransaction();
        try {
            // Hapus file surat undangan jika ada
            if ($draft->path_file_surat_usulan && Storage::disk('public')->exists($draft->path_file_surat_usulan)) {
                Storage::disk('public')->delete($draft->path_file_surat_usulan);
            }
            // Hapus detail pelaksana tugas yang terkait
            $draft->detailPelaksanaTugas()->delete();
            // Hapus surat tugas
            $draft->delete();

            \DB::commit();
            return redirect()->route('pengusul.draft')->with('success_message', 'Draft berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error menghapus draft Surat Tugas: '.$e->getMessage(), ['id' => $id, 'user_id' => $userId, 'trace' => $e->getTraceAsString()]);
            return redirect()->route('pengusul.draft')->with('error', 'Terjadi kesalahan saat menghapus draft.');
        }
    }

    /**
     * Menampilkan halaman riwayat pengusulan untuk Pengusul.
     * @param Request $request Untuk filter dan paginasi
     */
    public function history(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $historyPengusulan = SuratTugas::where('user_id', $userId)
                                      ->whereIn('status_surat', ['approved_by_direktur', 'diterbitkan', 'rejected_by_direktur', 'laporan_selesai']) // Status final
                                      ->when($search, function ($query) use ($search) {
                                          return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                       ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%")
                                                       ->orWhere('nomor_surat_tugas_resmi', 'like', "%{$search}%");
                                      })
                                      ->orderBy('updated_at', 'desc')
                                      ->paginate($perPage)
                                      ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('layouts.pengusul.history', compact('historyPengusulan', 'search'));
    }

    public function pengusulan()
    {
        $pegawais = Pegawai::all();
        $mahasiswa = Mahasiswa::all();
        return view('layouts.pengusul.pengusulan', compact('pegawais', 'mahasiswa'));
    }

    public function storePengusulan(Request $request)
    {
        // ... (metode ini tetap sama, pastikan tidak ada `ditugaskan_sebagai` di $request->except) ...
        $validator = Validator::make($request->all(), [
            'nama_kegiatan'        => 'required|string|max:255',
            'tempat_kegiatan'      => 'required|string',
            'diusulkan_kepada'     => 'required|string',
            'surat_undangan'       => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ditugaskan_sebagai'   => 'required|string|max:255',
            'tanggal_pelaksanaan'  => 'required|string',
            'alamat_kegiatan'      => 'required|string',
            'provinsi'             => 'required|string',
            'nomor_surat_usulan'   => 'required|string|max:255',
            'pembiayaan'           => 'required|string|in:Polban,Penyelenggara,Polban dan Penyelenggara,RM,PNBP', // Tambahkan RM/PNBP jika bisa dipilih di awal
            'pagu_desentralisasi'  => 'boolean',
            'pagu_nominal'         => 'nullable|numeric|required_if:pagu_desentralisasi,1', // Tambahkan validasi ini jika ada inputnya
            'pegawai_ids'          => 'nullable|array',
            'pegawai_ids.*'        => 'exists:pegawai,id',
            'mahasiswa_ids'        => 'nullable|array',
            'mahasiswa_ids.*'      => 'exists:mahasiswa,id',
            'status_pengajuan'     => 'required|string|in:draft,diajukan',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi Pengusulan Gagal:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->except([
                '_token',
                'pembiayaan_option',
                'pegawai_ids',
                'mahasiswa_ids',
                'status_pengajuan'
            ]);

            $tanggal_parts = explode(' → ', $data['tanggal_pelaksanaan']);
            $tanggal_berangkat = Carbon::createFromFormat('d/m/Y', $tanggal_parts[0])->format('Y-m-d');
            $tanggal_kembali = Carbon::createFromFormat('d/m/Y', $tanggal_parts[1])->format('Y-m-d');

            $surat_undangan_path = null;
            if ($request->hasFile('surat_undangan')) {
                $surat_undangan_path = $request->file('surat_undangan')->store('surat_undangan', 'public');
            }

            $status_surat_db = $request->input('status_pengajuan') === 'draft' ? 'draft' : 'pending_wadir_review';

            $suratTugas = SuratTugas::create([
                'user_id'                    => auth()->id(),
                'nomor_surat_usulan_jurusan' => $data['nomor_surat_usulan'],
                'diusulkan_kepada'           => $data['diusulkan_kepada'],
                'perihal_tugas'              => $data['nama_kegiatan'],
                'tempat_kegiatan'            => $data['tempat_kegiatan'],
                'alamat_kegiatan'            => $data['alamat_kegiatan'],
                'kota_tujuan'                => $data['provinsi'],
                'tanggal_berangkat'          => $tanggal_berangkat,
                'tanggal_kembali'            => $tanggal_kembali,
                'status_surat'               => $status_surat_db,
                'catatan_revisi'             => null,
                'path_file_surat_usulan'     => $surat_undangan_path,
                'path_file_surat_tugas_final'=> null,
                'sumber_dana'                => $data['pembiayaan'],
                'pagu_desentralisasi'        => $request->boolean('pagu_desentralisasi'),
                'pagu_nominal'               => $request->input('pagu_nominal') ?? null, // Simpan nominal pagu
                'tanggal_paraf_wadir'        => null,
                'tanggal_persetujuan_direktur' => null,
                'is_surat_perintah_langsung' => false,
                'ditugaskan_sebagai'         => $data['ditugaskan_sebagai'],
            ]);

            $pegawai_ids = array_unique($request->input('pegawai_ids', []));
            $mahasiswa_ids = array_unique($request->input('mahasiswa_ids', []));
            $status_sebagai_setiap_personel = $data['ditugaskan_sebagai'];

            foreach ($pegawai_ids as $pegawai_id) {
                $suratTugas->detailPelaksanaTugas()->create([
                    'personable_id'   => $pegawai_id,
                    'personable_type' => Pegawai::class,
                    'status_sebagai'  => $status_sebagai_setiap_personel,
                ]);
            }

            foreach ($mahasiswa_ids as $mahasiswa_id) {
                $suratTugas->detailPelaksanaTugas()->create([
                    'personable_id'   => $mahasiswa_id,
                    'personable_type' => Mahasiswa::class,
                    'status_sebagai'  => $status_sebagai_setiap_personel,
                ]);
            }

            Log::info('Pengusulan berhasil disimpan. ID Surat Tugas: ' . $suratTugas->surat_tugas_id, $suratTugas->toArray());
            return response()->json(['success' => true, 'message' => 'Pengusulan berhasil diajukan!'], 200);

        } catch (\Exception $e) {
            Log::error('Error menyimpan pengusulan: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage()], 500);
        }
    }
}