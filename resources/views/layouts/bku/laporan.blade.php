@extends('layouts.bku.layout')

@section('title', 'Laporan')
@section('bku_content')
<div class="laporan-container px-4 py-3">
    <h1 class="laporan-page-title mb-4">Laporan</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/laporan_bku.css') }}">
@endpush