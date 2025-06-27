<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Untuk debugging jika perlu
use Illuminate\Support\Facades\Validator; // Untuk validasi

class MenuPengusulanController extends Controller
{
    public function pengusulan(){
        return view('layouts.pengusul.pengusulan');
    }

    public function storePengusulan(Request $request)
    {
        // 1. Validasi data (Sangat Direkomendasikan)
        $validator = Validator::make($request->all(), [
            'nama_kegiatan' => 'required|string|max:255',
            'tempat_kegiatan' => 'required|string',
            'diusulkan_kepada' => 'required|string',
            'surat_undangan' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // contoh: pdf,doc,docx, max 2MB
            'ditugaskan_sebagai' => 'required|string|max:255',
            'tanggal_pelaksanaan' => 'required|string', // Pertimbangkan validasi format tanggal jika perlu
            'alamat_kegiatan' => 'required|string',
            'pembiayaan' => 'required|string|in:Polban,Penyelenggara,polban_penyelenggara',
            'pagu_desentralisasi' => 'nullable|boolean',
            'pagu_nominal' => 'nullable|numeric|required_if:pagu_desentralisasi,1', // Wajib jika desentralisasi dicentang
            'provinsi' => 'required|string',
            'nomor_surat_usulan' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pengusulan.form') // Kembali ke form
                        ->withErrors($validator)      // Kirim error validasi
                        ->withInput();                // Kirim input sebelumnya
        }

        // 2. Proses data
        // Contoh: Mengambil semua data
        $data = $request->all();
        Log::info('Data Pengusulan Diterima:', $data); // Cek di storage/logs/laravel.log

        // Handle file upload jika ada
        if ($request->hasFile('surat_undangan')) {
            $filePath = $request->file('surat_undangan')->store('surat_undangan', 'public'); // Simpan di storage/app/public/surat_undangan
            $data['surat_undangan_path'] = $filePath; // Simpan path ke database, bukan file nya
        }
        
        // Pastikan 'pagu_desentralisasi' ada atau set default jika tidak dicentang
        $data['pagu_desentralisasi'] = $request->has('pagu_desentralisasi');


        // 3. Simpan ke database (Contoh jika Anda punya model Pengusulan)
        /*
        try {
            // $pengusulan = new Pengusulan(); // Ganti dengan nama Model Anda
            // $pengusulan->nama_kegiatan = $data['nama_kegiatan'];
            // $pengusulan->tempat_kegiatan = $data['tempat_kegiatan'];
            // // ... isi field lainnya
            // if (isset($data['surat_undangan_path'])) {
            //    $pengusulan->surat_undangan = $data['surat_undangan_path'];
            // }
            // $pengusulan->pagu_desentralisasi = $data['pagu_desentralisasi'];
            // $pengusulan->pagu_nominal = $data['pagu_nominal'] ?? null; // Jika null, set null
            // // ...
            // $pengusulan->save();

            // return redirect()->route('pengusulan.form')->with('success', 'Pengusulan berhasil diajukan!');
        } catch (\Exception $e) {
            Log::error('Error menyimpan pengusulan: '.$e->getMessage());
            return redirect()->route('pengusulan.form')->with('error', 'Terjadi kesalahan saat menyimpan data.')->withInput();
        }
        */

        // Untuk sekarang, kita hanya akan dump data dan redirect
        // dd($data); // Dump and die untuk melihat data yang diterima

        // 4. Redirect atau response
        return redirect()->route('pengusulan.form') // Ganti dengan route tujuan setelah sukses
                         ->with('success', 'Pengusulan berhasil diajukan! Data diterima (cek log).');
    }
}