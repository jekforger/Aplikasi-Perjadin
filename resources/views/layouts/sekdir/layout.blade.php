{{-- resources/views/sekdir/layout.blade.php --}}
@extends('layouts.main')

@section('sidebar')
    @php
        $userRole = null; // Inisialisasi default
        $roleDisplayName = 'Pengguna'; // Inisialisasi default
        if (\Auth::check()) { // Pastikan user sudah login
            $userRole = \Auth::user()->role;
            $loginController = new \App\Http\Controllers\Auth\LoginController(); // Buat instance controller
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }
    @endphp
    @include('layouts.sekdir.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
@endsection

@section('content')
    @yield('sekdir_content')
@endsection