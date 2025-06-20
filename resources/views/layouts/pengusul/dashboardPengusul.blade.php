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

  {{-- Card Detail Pengusulan Terbaru --}}
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pengusulan Terbaru Saya</h6>
    </div>
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