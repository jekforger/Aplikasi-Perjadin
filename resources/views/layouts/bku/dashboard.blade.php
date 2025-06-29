@extends('layouts.bku.layout')

@section('title', 'Dashboard')

@section('bku_content')
<div class="dashboard-container px-4 py-3">
    <h1 class="dashboard-page-title mb-4">Dashboard BKU</h1>

    {{-- Ringkasan --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Total Pengusulan</p>
                <h5 class="fw-bold mb-2">{{ $totalPenugasan ?? 0 }}</h5>
                <i class="bi bi-file-earmark"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Surat Tugas Baru</p>
                <h5 class="fw-bold mb-2">{{ $penugasanBaru ?? 0 }}</h5>
                <i class="bi bi-file-earmark-plus"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Bertugas</p>
                <h5 class="fw-bold mb-2">{{ $sedangBertugas ?? 0 }}</h5>
                <i class="bi bi-briefcase"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Laporan Belum Selesai</p>
                <h5 class="fw-bold mb-2">{{ $laporanBelumSelesai ?? 0 }}</h5>
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    {{-- Tabel Detail --}}
    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Detail Penugasan</h5>

        {{-- Pencarian --}}
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('bku.dashboard') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2"
                    placeholder="Cari Tugas..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Cari
                </button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengusulan</th>
                        <th>Tanggal Berangkat</th>
                        <th>Nomor Surat Tugas</th>
                        <th>Sumber Dana</th>
                        <th>Status Laporan</th>
                        <th>Tanggungan Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarTugas as $index => $tugas)
                        <tr>
                            <td>{{ $loop->iteration + ($daftarTugas->currentPage() - 1) * $daftarTugas->perPage() }}</td>
                            <td>{{ $tugas->created_at->format('d M Y') }}</td>
                            <td>{{ $tugas->tanggal_berangkat->format('d M Y') }}</td>
                            <td>{{ $tugas->nomor_surat ?? '-' }}</td>
                            <td>{{ $tugas->sumber_dana ?? '-' }}</td>
                            <td>
                                @php
                                    $status = 'Belum Upload';
                                    if ($tugas->laporanPerjalananDinas) {
                                        $statusLaporan = $tugas->laporanPerjalananDinas->status_laporan;
                                        if ($statusLaporan === 'Diterima') {
                                            $status = 'Selesai';
                                        } elseif ($statusLaporan === 'Dikembalikan') {
                                            $status = 'Belum Upload';
                                        } else {
                                            $status = 'Belum Upload';
                                        }
                                    } elseif ($tugas->tanggal_kembali >= \Carbon\Carbon::today()) {
                                        $status = 'Sedang Bertugas';
                                    }
                                @endphp

                                @if($status === 'Selesai')
                                    <span class="badge bg-success">{{ $status }}</span>
                                @elseif($status === 'Sedang Bertugas')
                                    <span class="badge bg-primary">{{ $status }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ $status }}</span>
                                @endif
                            </td>
                            <td>{{ $tugas->tanggungan_biaya ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data penugasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $daftarTugas->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bku.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
