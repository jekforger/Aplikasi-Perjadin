<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BkuController extends Controller
{
    public function dashboard() {
        return view('layouts.bku.dashboard');
    }

    public function bukti() {
        return view('layouts.bku.bukti');
    }

    public function laporan() {
        return view('layouts.bku.laporan');
    }
}
