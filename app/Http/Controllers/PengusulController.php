<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Models\SuratTugas;
use App\Models\DetailPelaksanaTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PengusulController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $totalUsulan = SuratTugas::where('user_id', $userId)->count();
        $laporanSelesai = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'laporan_selesai')->count();
        $laporanBelumSelesai = SuratTugas::where('user_id', $userId)
                                         ->where('status_surat', 'approved_by_direktur')->count();
        $sedangBertugas = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'diterbitkan')
                                    ->whereDate('tanggal_berangkat', '<=', Carbon::today())
                                    ->whereDate('tanggal_kembali', '>=', Carbon::today())
                                    ->count();
        $dikembalikan = SuratTugas::where('user_id', $userId)
                                  ->whereIn('status_surat', ['rejected_by_wadir', 'reverted_by_wadir', 'rejected_by_direktur', 'reverted_by_direktur'])->count();
        $latestPengusulan = SuratTugas::where('user_id', $userId)
                                      ->orderBy('created_at', 'desc')
                                      ->take(5)
                                      ->get();

        return view('layouts.pengusul.dashboard', compact(
            'totalUsulan', 'laporanSelesai', 'laporanBelumSelesai', 'sedangBertugas', 'dikembalikan', 'latestPengusulan'
        ));
    }

    public function pilih(Request $request)
    {
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

    public function draft(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $draftPengusulan = SuratTugas::where('user_id', $userId)
                                    ->where('status_surat', 'draft')
                                    ->when($search, function ($query) use ($search) {
                                        return $query->where('perihal_tugas', 'like', "%{$search}%")
                                                    ->orWhere('nomor_surat_usulan_jurusan', 'like', "%{$search}%");
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->paginate($perPage)
                                    ->appends(['search' => $search, 'per_page' => $perPage]);
        return view('layouts.pengusul.draft', compact('draftPengusulan', 'search'));
    }

    public function deleteDraft($id)
    {
        $userId = Auth::id();
        $draft = SuratTugas::where('surat_tugas_id', $id)
                        ->where('user_id', $userId)
                        ->where('status_surat', 'draft')
                        ->firstOrFail();
        \DB::beginTransaction();
        try {
            if ($draft->path_file_surat_usulan && Storage::disk('public')->exists($draft->path_file_surat_usulan)) {
                Storage::disk('public')->delete($draft->path_file_surat_usulan);
            }
            $draft->detailPelaksanaTugas()->delete();
            $draft->delete();
            \DB::commit();
            return redirect()->route('pengusul.draft')->with('success_message', 'Draft berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error menghapus draft Surat Tugas: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('pengusul.draft')->with('error', 'Terjadi kesalahan saat menghapus draft.');
        }
    }

    public function editDraft($draft_id)
    {
        $draft = SuratTugas::with(['detailPelaksanaTugas.personable'])
            ->where('surat_tugas_id', $draft_id)
            ->where('user_id', Auth::id())
            ->where('status_surat', 'draft')
            ->firstOrFail();
        $pegawais = Pegawai::all();
        $mahasiswa = Mahasiswa::all();
        $draftData = [
            'nama_kegiatan' => $draft->perihal_tugas,
            'tempat_kegiatan' => $draft->tempat_kegiatan,
            'diusulkan_kepada' => $draft->diusulkan_kepada,
            'surat_undangan' => $draft->path_file_surat_usulan ? asset('storage/' . $draft->path_file_surat_usulan) : null,
            'ditugaskan_sebagai' => $draft->ditugaskan_sebagai,
            'tanggal_pelaksanaan' => Carbon::parse($draft->tanggal_berangkat)->format('d/m/Y') . ' → ' . Carbon::parse($draft->tanggal_kembali)->format('d/m/Y'),
            'alamat_kegiatan' => $draft->alamat_kegiatan,
            'provinsi' => $draft->kota_tujuan,
            'nomor_surat_usulan' => $draft->nomor_surat_usulan_jurusan,
            'pembiayaan' => $draft->sumber_dana,
            'pagu_desentralisasi' => $draft->pagu_desentralisasi,
            'pagu_nominal' => $draft->pagu_nominal,
        ];
        $selectedPersonel = $draft->detailPelaksanaTugas->map(function ($detail) {
            return [
                'id' => $detail->personable_id,
                'type' => $detail->personable_type === Pegawai::class ? 'pegawai' : 'mahasiswa',
                'nama' => $detail->personable->nama,
                'nip' => $detail->personable_type === Pegawai::class ? ($detail->personable->nip ?? '-') : null,
                'pangkat' => $detail->personable_type === Pegawai::class ? ($detail->personable->pangkat ?? '-') : null,
                'golongan' => $detail->personable_type === Pegawai::class ? ($detail->personable->golongan ?? '-') : null,
                'jabatan' => $detail->personable_type === Pegawai::class ? ($detail->personable->jabatan ?? '-') : null,
                'nim' => $detail->personable_type === Mahasiswa::class ? ($detail->personable->nim ?? '-') : null,
                'jurusan' => $detail->personable_type === Mahasiswa::class ? ($detail->personable->jurusan ?? '-') : null,
                'prodi' => $detail->personable_type === Mahasiswa::class ? ($detail->personable->prodi ?? '-') : null,
            ];
        })->toArray();
        \Log::info('Selected Personel for Draft ID: ' . $draft_id, $selectedPersonel);
        return view('layouts.pengusul.pengusulan', compact('pegawais', 'mahasiswa', 'draftData', 'selectedPersonel', 'draft'));
    }

    public function history(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $historyPengusulan = SuratTugas::where('user_id', $userId)
                                      ->whereIn('status_surat', ['approved_by_direktur', 'diterbitkan', 'rejected_by_direktur', 'laporan_selesai'])
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

    public function storePengusulan(Request $request, $draft_id = null)
    {
        Log::info('Received Data:', $request->all());
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
            'pembiayaan'           => 'required|string|in:Polban,Penyelenggara,Polban dan Penyelenggara,RM,PNBP',
            'pagu_desentralisasi'  => 'boolean',
            'pagu_nominal'         => 'nullable|numeric|required_if:pagu_desentralisasi,1',
            'pegawai_ids'          => 'nullable|array',
            'pegawai_ids.*'        => 'exists:pegawai,id',
            'mahasiswa_ids'        => 'nullable|array',
            'mahasiswa_ids.*'      => 'exists:mahasiswa,id',
            'status_pengajuan'     => 'required|string|in:draft,diajukan',
            'draft_id'             => 'nullable|exists:surat_tugas,surat_tugas_id',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi Pengusulan Gagal:', $validator->errors()->toArray());
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            \DB::beginTransaction();

            $data = $request->except(['_token', 'pembiayaan_option', 'pegawai_ids', 'mahasiswa_ids', 'status_pengajuan', '_method', 'draft_id']);
            $tanggal_parts = explode(' → ', $data['tanggal_pelaksanaan']);
            if (count($tanggal_parts) !== 2) {
                throw new \Exception('Format tanggal pelaksanaan tidak valid');
            }
            $tanggal_berangkat = Carbon::createFromFormat('d/m/Y', trim($tanggal_parts[0]))->format('Y-m-d');
            $tanggal_kembali = Carbon::createFromFormat('d/m/Y', trim($tanggal_parts[1]))->format('Y-m-d');
            $surat_undangan_path = null;
            if ($request->hasFile('surat_undangan')) {
                $surat_undangan_path = $request->file('surat_undangan')->store('surat_undangan', 'public');
            }

            $suratTugasData = [
                'user_id'                    => auth()->id(),
                'nomor_surat_usulan_jurusan' => $data['nomor_surat_usulan'],
                'diusulkan_kepada'           => $data['diusulkan_kepada'],
                'perihal_tugas'              => $data['nama_kegiatan'],
                'tempat_kegiatan'            => $data['tempat_kegiatan'],
                'alamat_kegiatan'            => $data['alamat_kegiatan'],
                'kota_tujuan'                => $data['provinsi'],
                'tanggal_berangkat'          => $tanggal_berangkat,
                'tanggal_kembali'            => $tanggal_kembali,
                'path_file_surat_usulan'     => $surat_undangan_path,
                'sumber_dana'                => $data['pembiayaan'],
                'pagu_desentralisasi'        => $request->boolean('pagu_desentralisasi'),
                'pagu_nominal'               => $request->input('pagu_nominal') ?? null,
                'ditugaskan_sebagai'         => $data['ditugaskan_sebagai'],
                'status_surat'               => $request->input('status_pengajuan') === 'diajukan' ? 'pending_wadir_review' : 'draft',
                'catatan_revisi'             => null,
                'path_file_surat_tugas_final' => null,
                'tanggal_paraf_wadir'        => null,
                'tanggal_persetujuan_direktur' => null,
                'is_surat_perintah_langsung' => false,
            ];

            if ($request->input('status_pengajuan') === 'diajukan' && $draft_id) {
                // Jika draft diusulkan, update draft yang sudah ada
                $suratTugas = SuratTugas::where('surat_tugas_id', $draft_id)
                                        ->where('user_id', auth()->id())
                                        ->where('status_surat', 'draft')
                                        ->firstOrFail();
                if ($suratTugas->path_file_surat_usulan && $surat_undangan_path && Storage::disk('public')->exists($suratTugas->path_file_surat_usulan)) {
                    Storage::disk('public')->delete($suratTugas->path_file_surat_usulan);
                }
                $suratTugas->update(array_merge($suratTugasData, [
                    'path_file_surat_usulan' => $surat_undangan_path ?: $suratTugas->path_file_surat_usulan,
                ]));
                $suratTugas->detailPelaksanaTugas()->delete();
            } else {
                if ($draft_id) {
                    // Jika update draft tanpa mengusulkan
                    $suratTugas = SuratTugas::where('surat_tugas_id', $draft_id)
                                            ->where('user_id', auth()->id())
                                            ->where('status_surat', 'draft')
                                            ->firstOrFail();
                    if ($suratTugas->path_file_surat_usulan && $surat_undangan_path && Storage::disk('public')->exists($suratTugas->path_file_surat_usulan)) {
                        Storage::disk('public')->delete($suratTugas->path_file_surat_usulan);
                    }
                    $suratTugas->update(array_merge($suratTugasData, [
                        'path_file_surat_usulan' => $surat_undangan_path ?: $suratTugas->path_file_surat_usulan,
                    ]));
                    $suratTugas->detailPelaksanaTugas()->delete();
                } else {
                    // Jika buat draft baru atau pengajuan langsung tanpa draft
                    $suratTugas = SuratTugas::create($suratTugasData);
                }
            }

            $pegawai_ids = $request->input('pegawai_ids', []);
            $mahasiswa_ids = $request->input('mahasiswa_ids', []);
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

            \DB::commit();
            Log::info('Pengusulan berhasil diproses. Status: ' . $request->input('status_pengajuan') . ', Surat Tugas ID: ' . $suratTugas->surat_tugas_id);
            $message = 'Pengusulan berhasil ' . ($request->input('status_pengajuan') === 'draft' ? 'disimpan sebagai draft' : 'diajukan dan menunggu review Wakil Direktur') . '!';
            return response()->json(['success' => true, 'message' => $message], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error menyimpan pengusulan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()], 500);
        }
    }
}