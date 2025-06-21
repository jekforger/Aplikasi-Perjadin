{{-- resources/views/pengusul/layout.blade.php --}}
@extends('layouts.main')

@section('sidebar')
    {{-- Ambil userRole dan roleDisplayName langsung di sini --}}
    @php
        $userRole = null; // Inisialisasi default
        $roleDisplayName = 'Pengguna'; // Inisialisasi default
        if (\Auth::check()) { // Pastikan user sudah login
            $userRole = \Auth::user()->role;
            $loginController = new \App\Http\Controllers\Auth\LoginController(); // Buat instance controller
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }
    @endphp
    @include('layouts.pengusul.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
@endsection

@section('content')
    @yield('pengusul_content')
@endsection