@extends('layouts.pengusul.layout')

@section('title', 'Dashboard')
@section('pengusul_content')
<div class="dashboard-container px-4 py-3">
  <h1 class="dashboard-page-title mb-4">Dashboard</h1>

  <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Total Usulan</p>
                <h5 class="fw-bold mb-2">{{ $totalUsulan ?? 0 }}</h5>
                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Laporan Selesai</p>
                <h5 class="fw-bold mb-2">{{ $laporanSelesai ?? 0 }}</h5>
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
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Bertugas</p>
                <h5 class="fw-bold mb-2">{{ $sedangBertugas ?? 0 }}</h5>
                <i class="bi bi-people fs-4 text-info"></i>
            </div>
        </div>
	      <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Dikembalikan</p>
                <h5 class="fw-bold mb-2">{{ $dikembalikan ?? 0 }}</h5>
                <i class="bi bi-people fs-4 text-info"></i>
            </div>
        </div>
    </div>

  {{-- Card Detail Pengusulan Terbaru --}}
  <div class="p-4 shadow-sm bg-white rounded text-left">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengusulan</th>
                    <th>Nama Kegiatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestPengusulan as $index => $st)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $st->created_at->format('d M Y') }}</td>
                    <td>{{ $st->perihal_tugas }}</td>
                    <td>
                        @php
                            $badgeClass = '';
                            switch ($st->status_surat) {
                                case 'draft': $badgeClass = 'bg-secondary'; break;
                                case 'pending_wadir_review': $badgeClass = 'bg-warning text-dark'; break;
                                case 'approved_by_wadir': $badgeClass = 'bg-info'; break;
                                case 'rejected_by_wadir': $badgeClass = 'bg-danger'; break;
                                case 'reverted_by_wadir': $badgeClass = 'bg-info text-dark'; break;
                                case 'approved_by_direktur': $badgeClass = 'bg-success'; break;
                                case 'rejected_by_direktur': $badgeClass = 'bg-danger'; break;
                                case 'reverted_by_direktur': $badgeClass = 'bg-warning text-dark'; break;
                                case 'diterbitkan': $badgeClass = 'bg-primary'; break;
                                case 'laporan_selesai': $badgeClass = 'bg-success'; break;
                                default: $badgeClass = 'bg-light text-dark'; break;
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                    </td>
                    <td>
                        {{-- Aksi View / Edit --}}
                        <a href="#" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada pengusulan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard_pengusul.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush