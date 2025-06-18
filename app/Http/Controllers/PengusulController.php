<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PengusulController extends Controller
{
    // Halaman dashboard pengusul
    public function dashboard()
    {
        return view('layouts.pengusul.dashboard');
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
        return view('layouts.pengusul.pengusulan');
    }

    // Menyimpan data pengusulan ke database
    public function storePengusulan(Request $request)
    {
        // Validasi input (disesuaikan dengan field yang digunakan)
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            // Tambahkan field lainnya jika ada
        ]);

        // Simpan ke database (contoh menggunakan model `Pengusulan`)
        // Pengusulan::create($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('pengusul.pengusulan')->with('success', 'Pengusulan berhasil dikirim.');
    }
}
