@extends('layouts.pengusul.pagePengusul')

@section('content')
<div class="card-container">
  <h2 class="page-title mb-4">Dashboard</h2>

  {{-- Kotak Statistik --}}
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4 px-4">
    <div class="col">
      <div class="dashboard-card bg-white border shadow-sm p-3 text-center rounded-3">
        <h6 class="text-muted mb-1">Total Usulan</h6>
        <h3 class="text-dark mb-0">{{ $totalUsulan ?? 0 }}</h3>
      </div>
    </div>
    <div class="col">
      <div class="dashboard-card bg-white border shadow-sm p-3 text-center rounded-3">
        <h6 class="text-muted mb-1">Laporan Selesai</h6>
        <h3 class="text-dark mb-0">{{ $laporanSelesai ?? 0 }}</h3>
      </div>
    </div>
    <div class="col">
      <div class="dashboard-card bg-white border shadow-sm p-3 text-center rounded-3">
        <h6 class="text-muted mb-1">Belum Selesai</h6>
        <h3 class="text-dark mb-0">{{ $laporanBelumSelesai ?? 0 }}</h3>
      </div>
    </div>
    <div class="col">
      <div class="dashboard-card bg-white border shadow-sm p-3 text-center rounded-3">
        <h6 class="text-muted mb-1">Sedang Bertugas</h6>
        <h3 class="text-dark mb-0">{{ $sedangBertugas ?? 0 }}</h3>
      </div>
    </div>
    <div class="col">
      <div class="dashboard-card bg-white border shadow-sm p-3 text-center rounded-3">
        <h6 class="text-muted mb-1">Dikembalikan</h6>
        <h3 class="text-dark mb-0">{{ $dikembalikan ?? 0 }}</h3>
      </div>
    </div>
  </div>

  {{-- Card Detail --}}
  <div class="card">
    <div class="card-body px-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h2 class="h4 font-weight-bold mb-2">Detail Pengusulan Saya</h2>
          <p class="text-muted">Ini Dashboard Pengusul</p>
        </div>
      </div>
      {{-- Di sini nanti bisa ditambahkan tabel atau list pengusulan terbaru dari pengusul yang login --}}
    </div>
  </div>
</div>
@endsection