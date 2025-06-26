@extends('layouts.pelaksana.layout')

@section('title', 'Dashboard')
@section('pelaksana_content')
<div class="dashboard-container px-4 py-3">
    <h1 class="dashboard-page-title mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Total Penugasan</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Penugasan Baru</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-plus fs-4 text-success"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Laporan Belum Selesai</p>
                <h5 class="fw-bold mb-2">{{ $laporanBelumSelesai ?? 0 }}</h5>
                <i class="bi bi-bar-chart-line fs-4 text-warning"></i>
            </div>
        </div>
    <div class="p-4 shadow-sm bg-white rounded text-left">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pelaksana.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
