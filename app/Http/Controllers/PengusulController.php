<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PengusulController extends Controller
{
    public function dashboard()
    {
        // Data dummy untuk dashboard pengusul, bisa diganti dengan data real nanti
        $totalUsulan = 15;
        $laporanSelesai = 8;
        $laporanBelumSelesai = 5;
        $sedangBertugas = 2;
        $dikembalikan = 1;

        return view('layouts.pengusul.dashboardPengusul', compact('totalUsulan', 'laporanSelesai', 'laporanBelumSelesai', 'sedangBertugas', 'dikembalikan'));
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

    public function status()
    {
        return view('layouts.pengusul.status');
    }

    public function draft()
    {
        return view('layouts.pengusul.draft');
    }

    public function history()
    {
        return view('layouts.pengusul.history');
    }

    // Halaman form pengusulan
    public function pengusulan()
    {
        // Ambil semua data pegawai dan mahasiswa dari database
        $pegawais = Pegawai::all();
        $mahasiswa = Mahasiswa::all();

        // Teruskan data ke view
        return view('layouts.pengusul.pengusulan', compact('pegawais', 'mahasiswa'));
    }

    // Menyimpan data pengusulan ke database
    public function storePengusulan(Request $request)
    {
        // Logika validasi untuk langkah pertama form
        $rules = [
            'nama_kegiatan' => 'required|string|max:255',
            'tempat_kegiatan' => 'required|string|max:255',
            'diusulkan_kepada' => 'required|string|max:255',
            'surat_undangan' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'ditugaskan_sebagai' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|string', // Contoh: "01/01/2024 → 05/01/2024"
            'alamat_kegiatan' => 'required|string|max:255',
            'pembiayaan' => 'required|string|in:Polban,Penyelenggara,Polban dan Penyelenggara',
            'pagu_desentralisasi' => 'nullable|boolean',
            'pagu_nominal' => 'nullable|numeric|required_if:pagu_desentralisasi,1',
            'provinsi' => 'required|string|max:255',
            'nomor_surat_usulan' => 'required|string|max:255',
            // Validasi untuk personel yang dipilih dari langkah kedua
            'pegawai_ids' => 'nullable|array',
            'pegawai_ids.*' => 'exists:pegawai,id', // Pastikan setiap ID pegawai ada di tabel
            'mahasiswa_ids' => 'nullable|array',
            'mahasiswa_ids.*' => 'exists:mahasiswa,id', // Pastikan setiap ID mahasiswa ada di tabel
            'draft_flag' => 'nullable|boolean', // Untuk menandai apakah ini draft atau usulan final
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Jika validasi gagal, kembalikan ke form dan berikan error
            // Menggunakan flash session untuk menandai bahwa form pertama harus aktif
            $request->session()->flash('initial_form_active', true);
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Handle file upload jika ada
        if ($request->hasFile('surat_undangan')) {
            $filePath = $request->file('surat_undangan')->store('surat_undangan', 'public');
            $data['surat_undangan_path'] = $filePath; // Simpan path file
        } else {
            $data['surat_undangan_path'] = null;
        }

        // Tangani checkbox pagu_desentralisasi, pastikan bernilai boolean
        $data['pagu_desentralisasi'] = $request->has('pagu_desentralisasi');

        // Logika penyimpanan ke database (masih perlu diimplementasikan)
        try {
            // Anda perlu membuat Model 'Pengusulan' dan migrasinya terlebih dahulu
            // Contoh struktur minimal tabel 'pengusulan':
            // Schema::create('pengusulan', function (Blueprint $table) {
            //     $table->id();
            //     $table->foreignId('user_id')->constrained('users'); // Pengusul
            //     $table->string('nama_kegiatan');
            //     $table->text('tempat_kegiatan');
            //     $table->string('diusulkan_kepada');
            //     $table->string('surat_undangan_path')->nullable();
            //     $table->string('ditugaskan_sebagai');
            //     $table->string('tanggal_pelaksanaan'); // 'DD/MM/YYYY → DD/MM/YYYY'
            //     $table->text('alamat_kegiatan');
            //     $table->string('pembiayaan');
            //     $table->boolean('pagu_desentralisasi')->default(false);
            //     $table->decimal('pagu_nominal', 15, 2)->nullable();
            //     $table->string('provinsi');
            //     $table->string('nomor_surat_usulan');
            //     $table->json('personel_terpilih')->nullable(); // Simpan ID pegawai/mahasiswa dalam JSON
            //     $table->string('status')->default('draft'); // 'draft', 'Menunggu Persetujuan Wakil Direktur', etc.
            //     $table->timestamps();
            // });

            // Simulasikan penyimpanan
            $status = $request->input('draft_flag') ? 'draft' : 'Menunggu Persetujuan Wakil Direktur';

            $pengusulanData = [
                // 'user_id' => auth()->id(), // Asumsi user yang login adalah pengusul
                'nama_kegiatan' => $data['nama_kegiatan'],
                'tempat_kegiatan' => $data['tempat_kegiatan'],
                'diusulkan_kepada' => $data['diusulkan_kepada'],
                'surat_undangan_path' => $data['surat_undangan_path'],
                'ditugaskan_sebagai' => $data['ditugaskan_sebagai'],
                'tanggal_pelaksanaan' => $data['tanggal_pelaksanaan'],
                'alamat_kegiatan' => $data['alamat_kegiatan'],
                'pembiayaan' => $data['pembiayaan'],
                'pagu_desentralisasi' => $data['pagu_desentralisasi'],
                'pagu_nominal' => $data['pagu_nominal'] ?? null,
                'provinsi' => $data['provinsi'],
                'nomor_surat_usulan' => $data['nomor_surat_usulan'],
                'personel_terpilih' => json_encode([
                    'pegawai' => $request->input('pegawai_ids', []),
                    'mahasiswa' => $request->input('mahasiswa_ids', []),
                ]),
                'status' => $status,
            ];

            // Uncomment baris di bawah ini dan sesuaikan jika Anda sudah punya model Pengusulan
            // \App\Models\Pengusulan::create($pengusulanData);

            Log::info('Data Pengusulan Diterima & Valid (simulasi disimpan):', $pengusulanData);

            return response()->json([
                'success' => true,
                'message' => $status === 'draft' ? 'Draft berhasil disimpan!' : 'Pengusulan berhasil diajukan!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error menyimpan pengusulan: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.'
            ], 500); // Kode status 500 untuk error server
        }
    }
}