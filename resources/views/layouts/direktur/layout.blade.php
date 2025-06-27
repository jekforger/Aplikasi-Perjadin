{{-- resources/views/direktur/layout.blade.php --}}
@extends('layouts.main') {{-- Mengextend layouts.main sebagai layout dasar --}}

@section('sidebar')
    @include('layouts.Direktur.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName]) {{-- Pastikan meneruskan variabel --}}
@endsection

@section('content')
    @yield('direktur_content') {{-- Yield untuk konten spesifik halaman Direktur --}}
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/direktur.css') }}"> {{-- Panggil CSS khusus Direktur --}}
@endpush