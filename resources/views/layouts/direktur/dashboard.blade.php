{{-- resources/views/direktur/dashboard.blade.php --}}
@extends('layouts.direktur.layout') {{-- Mengextend layout khusus Direktur --}}

@section('direktur_content') {{-- Memasukkan konten ke yield 'direktur_content' --}}
<div class="container-fluid dashboard-direktur-container"> {{-- Kelas kustom untuk Direktur --}}
    <h1 class="dashboard-page-title mb-4">Dashboard</h1>

    {{-- Kotak Info Dashboard --}}
    <div class="row dashboard-info-boxes g-3 mb-4">
        {{-- Kotak Total Pengusulan --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="dashboard-card shadow h-100 py-2 border-left-blue">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-blue text-uppercase">
                                Total Pengusulan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['total_pengusulan'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text info-box-icon text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Dalam Proses (Wadir) --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="dashboard-card shadow h-100 py-2 border-left-green"> {{-- Warna hijau --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-green text-uppercase">
                                DALAM PROSES (WADIR)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_wadir'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history info-box-icon text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Dalam Proses (BKU) --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="dashboard-card shadow h-100 py-2 border-left-purple"> {{-- Warna ungu --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-purple text-uppercase">
                                DALAM PROSES (BKU)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_bku'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet-fill info-box-icon text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Bertugas --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="dashboard-card shadow h-100 py-2 border-left-orange">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-orange text-uppercase">
                                BERTUGAS
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['bertugas'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-walking info-box-icon text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Pengusulan --}}
    <div class="card shadow mb-4 dashboard-table-card">
        <div class="card-header py-3 d-flex justify-content-between align-items-center table-header-flex">
            <h6 class="m-0 font-weight-bold text-gray-800 table-title">Detail</h6>
            <div class="table-search-box input-group w-auto">
                <input type="text" class="form-control form-control-sm custom-search-input" placeholder="Search...">
                <button class="btn btn-primary btn-sm custom-search-button"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered dashboard-data-table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal Berangkat</th>
                            <th>Nomor Surat</th>
                            <th>Sumber Dana</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengusulanDetails as $detail)
                        <tr>
                            <td>{{ $detail['tgl_berangkat'] }}</td>
                            <td>{{ $detail['nomor_surat'] }}</td> {{-- Kolom Nomor Surat --}}
                            <td>{{ $detail['sumber_dana'] }}</td>
                            <td>
                                @if($detail['status'] == 'Disetujui Wadir')
                                    <span class="badge status-badge badge-success">Disetujui Wadir</span>
                                @elseif($detail['status'] == 'Ditolak Wadir')
                                    <span class="badge status-badge badge-danger">Ditolak Wadir</span>
                                @elseif($detail['status'] == 'Sedang Diproses')
                                    <span class="badge status-badge badge-warning text-dark">Sedang Diproses</span>
                                @elseif($detail['status'] == 'Selesai')
                                    <span class="badge status-badge badge-secondary text-white">Selesai</span>
                                @else
                                    <span class="badge status-badge badge-info text-white">{{ $detail['status'] }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm custom-view-button">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pengusulan.</td> {{-- Kolom disesuaikan --}}
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Bagian pagination/rows per page --}}
            <div class="d-flex justify-content-between align-items-center mt-3 table-pagination-controls">
                <div>
                    Rows per page: 
                    <select class="form-select form-select-sm d-inline-block w-auto custom-pagination-select">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
                <div>
                    <span class="me-2">1 - 10 of {{ count($pengusulanDetails) }}</span>
                    <button class="btn btn-outline-secondary btn-sm custom-pagination-button"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-outline-secondary btn-sm custom-pagination-button"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/direktur.css') }}">
@endpush