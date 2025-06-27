{{-- resources/views/layouts/Wadir/persetujuan.blade.php --}}
@extends('layouts.Wadir.layout')

@section('title', 'Persetujuan')
@section('wadir_content')
<div class="wadir-container px-4 py-3">
    <h1 class="wadir-page-title mb-4">Persetujuan</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Surat Tugas</h6>
        </div>
        <div class="card-body">
            {{-- Filter Status --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="btn-group" role="group" aria-label="Filter status">
                        <a href="{{ route('wadir.persetujuan', ['status' => 'pending', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                           class="btn {{ $filterStatus == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                           Menunggu Review
                        </a>
                        <a href="{{ route('wadir.persetujuan', ['status' => 'paraf', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                           class="btn {{ $filterStatus == 'paraf' ? 'btn-primary' : 'btn-outline-primary' }}">
                           Diparaf/Disetujui
                        </a>
                        <a href="{{ route('wadir.persetujuan', ['status' => 'ditolak', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                           class="btn {{ $filterStatus == 'ditolak' ? 'btn-primary' : 'btn-outline-primary' }}">
                           Ditolak
                        </a>
                        <a href="{{ route('wadir.persetujuan', ['status' => 'all', 'search' => request('search'), 'per_page' => request('per_page')]) }}"
                           class="btn {{ $filterStatus == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                           Semua
                        </a>
                    </div>
                </div>
                {{-- Search Bar --}}
                <form action="{{ route('wadir.persetujuan') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari Pengajuan..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <input type="hidden" name="status" value="{{ $filterStatus }}">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                </form>
            </div>

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
                        @forelse ($suratTugasList as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($suratTugasList->currentPage() - 1) * $suratTugasList->perPage() }}</td>
                            <td>{{ $st->pengusul->name ?? 'N/A' }}</td>
                            <td>{{ $st->perihal_tugas }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d/m/Y') }} → {{ $st->tanggal_kembali->format('d/m/Y') }}</td>
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
                            <td>
                                <a href="{{ route('wadir.review.surat_tugas', $st->surat_tugas_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                {{-- Tombol unduh PDF jika sudah ada file final dan statusnya sudah diterbitkan --}}
                                @if ($st->status_surat == 'diterbitkan' && $st->path_file_surat_tugas_final)
                                    <a href="{{ Storage::url($st->path_file_surat_tugas_final) }}" target="_blank" class="btn btn-success btn-sm mt-1">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengajuan dengan status ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $suratTugasList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wadir_content.css') }}">
@endpush