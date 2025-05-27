<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class DataMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswa = Mahasiswa::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%$search%")
                         ->orWhere('nim', 'like', "%$search%");
        })->paginate(10);

        return view('layouts.admin.dataMahasiswa', compact('mahasiswa', 'search'));
    }

    public function create()
    {
        return view('layouts.admin.tambahDataMahasiswa');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswa,nim',
            'jurusan' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);

        Mahasiswa::create($request->all());

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = Mahasiswa::findOrFail($id);
        return view('layouts.admin.editDataMahasiswa', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Mahasiswa::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswa,nim,' . $data->id,
            'jurusan' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
        ]);

        $data->update($request->all());

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $data = Mahasiswa::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
