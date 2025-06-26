@extends('layouts.bku.layout')

@section('title', 'Dashboard')
@section('bku_content')
<div class="dashboard-container px-4 py-3">
    <h1 class="dashboard-page-title mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Total Pengusulan</p>
                <h5 class="fw-bold mb-2">{{ $totalPengusulan ?? 0 }}</h5>
                <i class="bi bi-file-earmark"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Surat Tugas Baru</p>
                <h5 class="fw-bold mb-2">{{ $suratTugasBaru ?? 0 }}</h5>
                <i class="bi bi-file-earmark-plus"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Bertugas</p>
                <h5 class="fw-bold mb-2">{{ $bertugas ?? 0 }}</h5>
                <i class="bi bi-file-earmark-plus"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Laporan Belum Selesai</p>
                <h5 class="fw-bold mb-2">{{ $laporanBelumSelesai ?? 0 }}</h5>
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    <div class="p-4 shadow-sm bg-white rounded text-left">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/bku.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
