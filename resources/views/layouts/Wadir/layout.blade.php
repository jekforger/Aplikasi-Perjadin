{{-- resources/views/layouts/Wadir/layout.blade.php --}}
@extends('layouts.main') {{-- Meng-extend layout utama aplikasi --}}

@section('sidebar')
    @include('layouts.Wadir.partials.sidebar') {{-- Memuat sidebar khusus Wadir --}}
@endsection

@section('content')
    {{-- Main content for Wadir pages will be yielded here --}}
    {{-- Gunakan div .card-container untuk membungkus konten agar margin dan paddingnya sesuai dengan layout --}}
    <div class="card-container">
        @yield('wadir_content')
    </div>
@endsection

@push('styles')
    {{-- Masukkan CSS spesifik Wadir di sini jika belum di main.blade.php atau wadir.css --}}
    <link rel="stylesheet" href="{{ asset('css/wadir.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCXdJf3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    {{-- Script spesifik Wadir jika ada --}}
@endpush