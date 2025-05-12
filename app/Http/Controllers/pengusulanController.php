<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pengusulanController extends Controller
{
    public function index(){
        return view('layouts.pengusul.pagePengusul');
    }
}
