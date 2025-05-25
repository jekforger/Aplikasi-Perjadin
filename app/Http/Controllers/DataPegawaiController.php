<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $jabatan = $request->input('jabatan');
        
        $pegawais = Pegawai::when($search, function($query) use ($search) {
                return $query->where('nama', 'like', "%$search%")
                             ->orWhere('nip', 'like', "%$search%");
            })
            ->when($jabatan, function($query) use ($jabatan) {
                return $query->where('jabatan', $jabatan);
            })
            ->paginate(5);
            
        $jabatans = Pegawai::select('jabatan')->distinct()->get();
        
        return view('pegawai.index', compact('pegawais', 'jabatans'));
    }
}
