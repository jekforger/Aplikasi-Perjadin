<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SekdirController extends Controller
{
    public function dashboard()
    {
        return view('layouts.sekdir.dashboard');
    }

    public function nomorsurat()
    {
        return view('layouts.sekdir.nomorsurat');
    }
}
