<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuPengusulanController extends Controller
{
    public function pengusulan(){
        return view('layouts.pengusul.pengusulan');
    }
}
