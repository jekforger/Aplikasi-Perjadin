<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class DataPegawaiController extends Controller
{
    /**
     * Tampilkan daftar pegawai (dengan fitur search dan filter jabatan)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'nama');
        $direction = $request->input('direction', 'asc');
        $perPage = $request->input('per_page', 10);

        $pegawais = Pegawai::when($search, function ($query) use ($search) {
                return $query->where('nama', 'like', "%$search%")
                            ->orWhere('nip', 'like', "%$search%");
            })
            ->orderBy($sort, $direction)
            ->paginate($perPage);

        return view('layouts.admin.dataPegawai', compact('pegawais', 'sort', 'direction'));
    }



    /**
     * Tampilkan form tambah data pegawai
     */
    public function create()
    {
        return view('layouts.admin.tambahDataPegawai');
    }

    /**
     * Simpan data pegawai baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'required|string|unique:pegawai,nip',
            'pangkat'  => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:255',
            'jabatan'  => 'nullable|string|max:255',
        ]);

        Pegawai::create($validated);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data pegawai
     */
    public function edit($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return view('layouts.admin.editDataPegawai', compact('pegawai'));
    }

    /**
     * Update data pegawai
     */
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'      => 'required|string|unique:pegawai,nip,' . $pegawai->id,
            'pangkat'  => 'nullable|string|max:255',
            'golongan' => 'nullable|string|max:255',
            'jabatan'  => 'nullable|string|max:255',
        ]);

        $pegawai->update($validated);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Hapus data pegawai
     */
    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}
