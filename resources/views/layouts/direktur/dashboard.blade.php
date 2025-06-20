{{-- resources/views/direktur/dashboard.blade.php --}}
@extends('layouts.Direktur.layout') {{-- Mengextend layout khusus Direktur --}}

@section('direktur_content') {{-- Memasukkan konten ke yield 'direktur_content' --}}
<div class="container-fluid dashboard-direktur-container px-4 py-3"> {{-- Kelas kustom untuk Direktur --}}
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
            <div class="dashboard-card shadow h-100 py-2 border-left-green">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-green text-uppercase">
                                DALAM PROSES (WADIR)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_wadir'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history info-box-icon text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Dalam Proses (BKU) --}}
        <div class="col-xl-3 col-md-6 col-12">
            <div class="dashboard-card shadow h-100 py-2 border-left-purple">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="info-box-label text-purple text-uppercase">
                                DALAM PROSES (BKU)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_bku'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet-fill info-box-icon text-gray-300"></i>
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
            <form action="{{ route('direktur.dashboard') }}" method="GET" class="table-search-box input-group w-auto">
                <input type="text" name="search" class="form-control form-control-sm custom-search-input" placeholder="Search..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm custom-search-button"><i class="fas fa-search"></i></button>
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            </form>
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
                            <td>{{ $detail->tanggal_berangkat->format('d M Y') }}</td>
                            <td>{{ $detail->nomor_surat_usulan_jurusan }}</td> {{-- Contoh: gunakan nomor surat usulan --}}
                            <td>{{ $detail->sumber_dana }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    switch ($detail->status_surat) {
                                        case 'approved_by_wadir': $badgeClass = 'bg-info'; break;
                                        case 'rejected_by_direktur': $badgeClass = 'bg-danger'; break;
                                        case 'reverted_by_direktur': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'diterbitkan': $badgeClass = 'bg-success'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge status-badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($detail->status_surat)) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('direktur.review.surat_tugas', $detail->surat_tugas_id) }}" class="btn btn-info btn-sm custom-view-button">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data pengusulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Bagian pagination/rows per page --}}
            <div class="d-flex justify-content-between align-items-center mt-3 table-pagination-controls">
                <div>
                    Rows per page: 
                    <form action="{{ route('direktur.dashboard') }}" method="GET" id="direkturPerPageForm" class="d-inline-block">
                        <select name="per_page" class="form-select form-select-sm d-inline-block w-auto custom-pagination-select" onchange="this.form.submit()">
                            @foreach([10, 25, 50] as $size)
                                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    </form>
                </div>
                <div>
                    <span class="me-2">Showing {{ $pengusulanDetails->firstItem() ?? 0 }} - {{ $pengusulanDetails->lastItem() ?? 0 }} of {{ $pengusulanDetails->total() }}</span>
                    {{ $pengusulanDetails->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/direktur.css') }}">
@endpush