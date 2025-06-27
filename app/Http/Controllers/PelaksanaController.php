<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PelaksanaController extends Controller
{
    public function dashboard() {
        return view('layouts.pelaksana.dashboard');
    }
    
    public function bukti() {
        return view('layouts.pelaksana.bukti');
    }

    public function laporan() {
        return view('layouts.pelaksana.laporan');
    }

    public function dokumen() {
        return view('layouts.pelaksana.dokumen');
    }

    public function statusLaporan() {
        return view('layouts.pelaksana.statusLaporan');
    }
}
