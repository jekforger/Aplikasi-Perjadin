{{-- resources/views/layouts/pelaksana/layout.blade.php --}}
@extends('layouts.main')

@section('sidebar')
    {{-- Variabel akan diteruskan dari setiap metode di PelaksanaController --}}
    @include('layouts.pelaksana.partials.sidebar', [
        'userRole' => $userRole ?? 'pelaksana',
        'roleDisplayName' => $roleDisplayName ?? 'Pelaksana'
    ])
@endsection

@section('content')
    @yield('pelaksana_content')
@endsection