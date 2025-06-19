{{-- resources/views/wadir/dashboard.blade.php --}}
@extends('layouts.Wadir.layout') {{-- Mengextend layout khusus Wadir --}}

@section('wadir_content') {{-- Memasukkan konten ke yield 'wadir_content' --}}
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    {{-- Kotak Info Dashboard --}}
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pengusulan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['total_pengusulan'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Usulan Baru (Untuk Saya)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['usulan_baru'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Dalam Proses (Direktur)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_direktur'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Dikembalikan / Ditolak
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dikembalikan'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-undo-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
@extends('layouts.Wadir.layout')

@section('title', 'Dashboard')
@section('wadir_content')
<div class="dashboard-container px-4 py-3">
    <h1 class="dashboard-page-title mb-4">Dashboard</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Total Pengusulan</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Usulan Baru</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-plus fs-4 text-success"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Dalam Proses<br>(Direktur)</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-bar-chart-line fs-4 text-warning"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="p-4 shadow-sm bg-white rounded text-center dashboard-card">
                <p class="fw-semibold mb-1">Bertugas</p>
                <h5 class="fw-bold mb-2">0</h5>
                <i class="bi bi-people fs-4 text-info"></i>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Pengusulan untuk Review --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengusulan Menunggu Review Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pengusul</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Pembiayaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratTugasUntukReview as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($suratTugasUntukReview->currentPage() - 1) * $suratTugasUntukReview->perPage() }}</td>
                            <td>{{ $st->pengusul->name ?? 'N/A' }}</td>
                            <td>{{ $st->perihal_tugas }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d/m/Y') }} → {{ $st->tanggal_kembali->format('d/m/Y') }}</td>
                            <td>{{ $st->sumber_dana }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    switch ($st->status_surat) {
                                        case 'pending_wadir_review': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'reverted_by_wadir': $badgeClass = 'bg-info text-dark'; break;
                                        case 'reverted_by_direktur': $badgeClass = 'bg-danger'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('wadir.review.surat_tugas', $st->surat_tugas_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengusulan baru yang menunggu review Anda.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $suratTugasUntukReview->links() }}
                </div>
            </div>
        </div>
    <div class="p-4 shadow-sm bg-white rounded text-left">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wadir.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCXdJf3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
    {{-- Inisialisasi DataTables (opsional, karena paginasi Laravel sudah dipakai) --}}
    {{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": false,
                "info": false,
                "searching": true,
                "ordering": true
            });
        });
    </script> --}}
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush
