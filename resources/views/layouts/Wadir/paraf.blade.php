{{-- resources/views/layouts/Wadir/paraf.blade.php --}}
@extends('layouts.Wadir.layout')

@section('title', 'Surat Tugas Diparaf')
@section('wadir_content')
<div class="container-fluid">
    <h1 class="mb-4">Surat Tugas Diparaf</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat Tugas yang Telah Diparaf/Disetujui</h6>
        </div>
        <div class="card-body">
            {{-- Search Bar --}}
            <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('wadir.paraf') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari Surat Tugas..." value="{{ request('search') }}">
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
                            <th>Pengusul</th>
                            <th>Nama Kegiatan</th>
                            <th>Nomor Surat Usulan</th>
                            <th>Nomor Surat Resmi</th>
                            <th>Status</th>
                            <th>Terakhir Update</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratTugasDiparaf as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($suratTugasDiparaf->currentPage() - 1) * $suratTugasDiparaf->perPage() }}</td>
                            <td>{{ $st->pengusul->name ?? 'N/A' }}</td>
                            <td>{{ $st->perihal_tugas }}</td>
                            <td>{{ $st->nomor_surat_usulan_jurusan }}</td>
                            <td>{{ $st->nomor_surat_tugas_resmi ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    switch ($st->status_surat) {
                                        case 'approved_by_wadir': $badgeClass = 'bg-info'; break;
                                        case 'approved_by_direktur': $badgeClass = 'bg-success'; break;
                                        case 'diterbitkan': $badgeClass = 'bg-primary'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                            </td>
                            <td>{{ $st->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('wadir.review.surat_tugas', $st->surat_tugas_id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                {{-- Tombol unduh PDF jika sudah ada file final --}}
                                @if ($st->path_file_surat_tugas_final)
                                    <a href="{{ Storage::url($st->path_file_surat_tugas_final) }}" target="_blank" class="btn btn-success btn-sm mt-1">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada surat tugas yang telah diparaf.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $suratTugasDiparaf->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    {{-- Jika ada style spesifik untuk halaman ini, tambahkan di sini --}}
@endpush