{{-- resources/views/wadir/dashboard.blade.php --}}
@extends('layouts.Wadir.layout')

@section('title', 'Dashboard')
@section('wadir_content')
<div class="dashboard-container px-4 py-3">
    <h1 class="dashboard-page-title mb-4">Dashboard</h1>

    {{-- Kotak Statistik --}}
    <div class="row g-3 mb-4">
        {{-- Total Pengusulan --}}
        <div class="col-6 col-md-3">
            <div class="dashboard-card">
                <p class="dashboard-title">Total Pengusulan</p>
                <h5 class="dashboard-count">{{ $dashboardStats['total_pengusulan'] }}</h5>
                <i class="bi bi-file-earmark-text dashboard-icon text-primary"></i>
            </div>
        </div>
        {{-- Usulan Baru --}}
        <div class="col-6 col-md-3">
            <div class="dashboard-card">
                <p class="dashboard-title">Usulan Baru</p>
                <h5 class="dashboard-count">{{ $dashboardStats['usulan_baru'] }}</h5>
                <i class="bi bi-plus dashboard-icon text-success"></i>
            </div>
        </div>
        {{-- Dalam Proses (Direktur) --}}
        <div class="col-6 col-md-3">
            <div class="dashboard-card">
                <p class="dashboard-title">Dalam Proses<br>(Direktur)</p>
                <h5 class="dashboard-count">{{ $dashboardStats['dalam_proses_direktur'] }}</h5>
                <i class="bi bi-bar-chart-line dashboard-icon text-warning"></i>
            </div>
        </div>
        {{-- Bertugas --}}
        <div class="col-6 col-md-3">
            <div class="dashboard-card">
                <p class="dashboard-title">Bertugas</p>
                <h5 class="dashboard-count">{{ $dashboardStats['bertugas'] }}</h5>
                <i class="bi bi-people dashboard-icon text-info"></i>
            </div>
        </div>
        {{-- Dikembalikan / Ditolak (Tambahan) --}}
        {{-- Jika Anda ingin ini juga muncul sebagai kartu, tambahkan div ini --}}
        <div class="col-6 col-md-3">
            <div class="dashboard-card">
                <p class="dashboard-title">Dikembalikan/<br>Ditolak</p>
                <h5 class="dashboard-count">{{ $dashboardStats['dikembalikan_ditolak'] }}</h5>
                <i class="bi bi-x-circle dashboard-icon text-danger"></i>
            </div>
        </div>
    </div>

    {{-- HAPUS TABEL "Daftar Pengusulan Menunggu Review Anda" DARI SINI --}}
    {{-- Ini adalah blok yang akan dihapus dari dashboard.blade.php --}}
    {{--
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengusulan Menunggu Review Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
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
    </div>
    --}}


    {{-- Tabel "Detail" (SESUAI SCREENSHOT ANDA) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail</h6>
        </div>
        <div class="card-body">
            {{-- Search Bar --}}
            <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('wadir.dashboard') }}" method="GET" class="d-flex">
                    <input type="text" name="search_all" class="form-control me-2" placeholder="Search" value="{{ request('search_all') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengusulan</th>
                            <th>Tanggal Berangkat</th>
                            <th>Nomor Surat Pengusulan</th>
                            <th>Sumber Dana</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($allSuratTugas as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($allSuratTugas->currentPage() - 1) * $allSuratTugas->perPage() }}</td>
                            <td>{{ $st->created_at->format('d F Y') }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d F Y') }}</td>
                            <td>{{ $st->nomor_surat_usulan_jurusan }}</td>
                            <td>{{ $st->sumber_dana }}</td>
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
                                        default: $badgeClass = 'bg-light text-dark'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengusulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination dan Rows per page --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted">Rows per page:</span>
                    <form action="{{ route('wadir.dashboard') }}" method="GET" id="perPageForm">
                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach([5, 10, 25, 50] as $size)
                                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="search_all" value="{{ request('search_all') }}">
                    </form>
                </div>
                <div>
                    <span class="me-2 text-muted">Showing {{ $allSuratTugas->firstItem() ?? 0 }} to {{ $allSuratTugas->lastItem() ?? 0 }} of {{ $allSuratTugas->total() }} entries</span>
                    {{ $allSuratTugas->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wadir.css') }}">
@endpush