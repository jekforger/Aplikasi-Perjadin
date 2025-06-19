<?php

namespace App\Http\Controllers;

use App\Models\Pegawai; // Pastikan ini mengarah ke model Pegawai yang benar (setelah rename dari DataPegawai.php)
use App\Models\Mahasiswa;
use App\Models\SuratTugas; // Import model SuratTugas yang baru
use App\Models\DetailPelaksanaTugas; // Import model DetailPelaksanaTugas yang baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk logging
use Illuminate\Support\Facades\Validator; // Untuk validasi
use Carbon\Carbon; // Untuk parsing tanggal

class PengusulController extends Controller
{
    // Halaman dashboard pengusul
    public function dashboard()
    {
        // Untuk saat ini, dashboard pengusul masih statis
        // Anda bisa mengisi data nyata di sini nanti setelah data pengusulan tersimpan
        $totalUsulan = 0; // Ganti dengan query ke DB
        $laporanSelesai = 0; // Ganti dengan query ke DB
        $laporanBelumSelesai = 0; // Ganti dengan query ke DB
        $sedangBertugas = 0; // Ganti dengan query ke DB
        $dikembalikan = 0; // Ganti dengan query ke DB

        return view('layouts.pengusul.dashboardPengusul', compact('totalUsulan', 'laporanSelesai', 'laporanBelumSelesai', 'sedangBertugas', 'dikembalikan'));
    }

    // Metode ini tampaknya adalah halaman terpisah untuk memilih pengusul,
    // yang kemudian datanya digunakan di halaman pengusulan utama.
    // Jika tidak lagi digunakan sebagai halaman terpisah, rute dan method ini bisa dihapus.
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

    // Halaman status pengusulan
    public function status()
    {
        return view('layouts.pengusul.status');
    }

    // Halaman draft pengusulan
    public function draft()
    {
        return view('layouts.pengusul.draft');
    }

    // Halaman riwayat pengusulan
    public function history()
    {
        return view('layouts.pengusul.history');
    }

    // Halaman form pengusulan utama
    public function pengusulan()
    {
        // Ambil semua data pegawai dari database
        $pegawais = Pegawai::all();

        // Ambil semua data mahasiswa dari database
        $mahasiswa = Mahasiswa::all();

        // Teruskan data ke view
        return view('layouts.pengusul.pengusulan', compact('pegawais', 'mahasiswa'));
    }

    // Metode untuk memproses dan menyimpan data pengusulan
    public function storePengusulan(Request $request)
    {
        // Aturan validasi input
        $validator = Validator::make($request->all(), [
            'nama_kegiatan'        => 'required|string|max:255',
            'tempat_kegiatan'      => 'required|string',
            'diusulkan_kepada'     => 'required|string', // Pastikan ini required
            'surat_undangan'       => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Contoh: pdf,doc,docx, max 2MB
            'ditugaskan_sebagai'   => 'required|string|max:255',
            'tanggal_pelaksanaan'  => 'required|string', // Format "DD/MM/YYYY -> DD/MM/YYYY"
            'alamat_kegiatan'      => 'required|string',
            'provinsi'             => 'required|string',
            'nomor_surat_usulan'   => 'required|string|max:255',
            'pembiayaan'           => 'required|string|in:Polban,Penyelenggara,Polban dan Penyelenggara',
            'pagu_desentralisasi'  => 'boolean', // Akan menerima 0 atau 1 dari checkbox
            'pegawai_ids'          => 'nullable|array', // Array ID pegawai yang dipilih
            'pegawai_ids.*'        => 'exists:pegawai,id', // Validasi setiap ID pegawai harus ada di tabel 'pegawai'
            'mahasiswa_ids'        => 'nullable|array', // Array ID mahasiswa yang dipilih
            'mahasiswa_ids.*'      => 'exists:mahasiswa,id', // Validasi setiap ID mahasiswa harus ada di tabel 'mahasiswa'
            'status_pengajuan'     => 'required|string|in:draft,diajukan', // Status pengajuan dari frontend (Simpan Draft / Usulkan)
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            Log::error('Validasi Pengusulan Gagal:', $validator->errors()->toArray());
            // Mengembalikan respons JSON dengan error validasi
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        try {
            // Mengambil semua data dari request, kecuali token CSRF, opsi pembiayaan, dan ID personel
            // Pastikan 'diusulkan_kepada' dan 'ditugaskan_sebagai' TIDAK DIKECUALIKAN
            $data = $request->except([
                '_token',
                'pembiayaan_option', // Ini adalah nama radio button group, bukan nilai yang kita simpan
                'pegawai_ids',       // Akan disimpan di tabel pivot/detail
                'mahasiswa_ids',     // Akan disimpan di tabel pivot/detail
                'status_pengajuan'   // Akan digunakan untuk menentukan status awal di DB
            ]);

            // Parsing tanggal_pelaksanaan dari format "DD/MM/YYYY -> DD/MM/YYYY"
            $tanggal_parts = explode(' → ', $data['tanggal_pelaksanaan']);
            $tanggal_berangkat = Carbon::createFromFormat('d/m/Y', $tanggal_parts[0])->format('Y-m-d');
            $tanggal_kembali = Carbon::createFromFormat('d/m/Y', $tanggal_parts[1])->format('Y-m-d');

            // Menangani upload file surat undangan (jika ada)
            $surat_undangan_path = null;
            if ($request->hasFile('surat_undangan')) {
                // Simpan file di direktori storage/app/public/surat_undangan
                $surat_undangan_path = $request->file('surat_undangan')->store('surat_undangan', 'public');
            }

            // Menentukan status awal surat tugas di database
            // Jika dikirim dari tombol "Simpan Draft" maka status 'draft', jika dari "Usulkan" maka 'pending_wadir_review'
            $status_surat_db = $request->input('status_pengajuan') === 'draft' ? 'draft' : 'pending_wadir_review';

            // Membuat record baru di tabel 'surat_tugas'
            $suratTugas = SuratTugas::create([
'user_id'                    => auth()->id(),
                'nomor_surat_usulan_jurusan' => $data['nomor_surat_usulan'],
                'diusulkan_kepada'           => $data['diusulkan_kepada'],
                'perihal_tugas'              => $data['nama_kegiatan'],
                'tempat_kegiatan'            => $data['tempat_kegiatan'], // <-- PASTIKAN INI ADA
                'alamat_kegiatan'            => $data['alamat_kegiatan'], // <-- PASTIKAN INI ADA
                'kota_tujuan'                => $data['provinsi'],
                'tanggal_berangkat'          => $tanggal_berangkat,
                'tanggal_kembali'            => $tanggal_kembali,
                'status_surat'               => $status_surat_db,
                'catatan_revisi'             => null,
                'path_file_surat_usulan'     => $surat_undangan_path,
                'path_file_surat_tugas_final'=> null,
                'sumber_dana'                => $data['pembiayaan'],
                'pagu_desentralisasi'        => $request->boolean('pagu_desentralisasi'),
                'tanggal_paraf_wadir'        => null,
                'tanggal_persetujuan_direktur' => null,
                'is_surat_perintah_langsung' => false,
                'ditugaskan_sebagai'         => $data['ditugaskan_sebagai'],
                // 'wadir_approver_id' dan 'direktur_approver_id' akan diisi di tahap persetujuan
            ]);

            // Menyimpan DetailPelaksanaTugas untuk setiap personel yang dipilih
            $pegawai_ids = $request->input('pegawai_ids', []);
            $mahasiswa_ids = $request->input('mahasiswa_ids', []);
            $status_sebagai_setiap_personel = $data['ditugaskan_sebagai']; // Nilai "ditugaskan sebagai" yang sama untuk semua personel

            // Loop dan simpan untuk Pegawai
            foreach ($pegawai_ids as $pegawai_id) {
                $suratTugas->detailPelaksanaTugas()->create([
                    'personable_id'   => $pegawai_id,
                    'personable_type' => Pegawai::class, // Contoh: 'App\Models\Pegawai'
                    'status_sebagai'  => $status_sebagai_setiap_personel,
                ]);
            }

            // Loop dan simpan untuk Mahasiswa
            foreach ($mahasiswa_ids as $mahasiswa_id) {
                $suratTugas->detailPelaksanaTugas()->create([
                    'personable_id'   => $mahasiswa_id,
                    'personable_type' => Mahasiswa::class, // Contoh: 'App\Models\Mahasiswa'
                    'status_sebagai'  => $status_sebagai_setiap_personel,
                ]);
            }

            // Logging sukses dan mengembalikan respons JSON
            Log::info('Pengusulan berhasil disimpan. ID Surat Tugas: ' . $suratTugas->surat_tugas_id, $suratTugas->toArray());
            return response()->json(['success' => true, 'message' => 'Pengusulan berhasil diajukan!'], 200);

        } catch (\Exception $e) {
            // Logging error dan mengembalikan respons JSON error
            Log::error('Error menyimpan pengusulan: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'line' => $e->getLine(), // Baris kode di mana error terjadi
                'file' => $e->getFile()  // File di mana error terjadi
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage()], 500);
        }
    }
}