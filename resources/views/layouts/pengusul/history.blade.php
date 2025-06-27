@extends('layouts.pengusul.layout')

@section('title', 'History')
@section('pengusul_content')
<div class="pengusul-container px-4 py-3">
    <h1 class="pengusul-page-title mb-4">History</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Lengkap Surat Tugas</h6>
            <form action="{{ route('pengusul.history') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Riwayat..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengusulan</th>
                            <th>Tanggal Berangkat</th>
                            <th>Nomor Surat Pengantar</th>
                            <th>Nomor Surat Tugas</th> {{-- Nomor surat resmi --}}
                            <th>Tanggal Diterbitkan</th>
                            <th>Diusulkan Kepada</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historyPengusulan as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($historyPengusulan->currentPage() - 1) * $historyPengusulan->perPage() }}</td>
                            <td>{{ $st->created_at->format('d M Y') }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d M Y') }}</td>
                            <td>{{ $st->nomor_surat_usulan_jurusan }}</td>
                            <td>{{ $st->nomor_surat_tugas_resmi ?? '-' }}</td>
                            <td>{{ $st->tanggal_persetujuan_direktur ? $st->tanggal_persetujuan_direktur->format('d M Y H:i') : '-' }}</td>
                            <td>{{ $st->diusulkan_kepada }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    switch ($st->status_surat) {
                                        case 'approved_by_direktur': $badgeClass = 'bg-success'; break;
                                        case 'diterbitkan': $badgeClass = 'bg-primary'; break;
                                        case 'rejected_by_direktur': $badgeClass = 'bg-danger'; break;
                                        case 'laporan_selesai': $badgeClass = 'bg-info'; break;
                                        default: $badgeClass = 'bg-secondary'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                            </td>
                            <td>
                                {{-- Link ke halaman detail/view surat tugas (jika ada) --}}
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                {{-- Tombol Unduh PDF jika sudah diterbitkan --}}
                                @if ($st->status_surat == 'diterbitkan' && $st->path_file_surat_tugas_final)
                                    <a href="{{ Storage::url($st->path_file_surat_tugas_final) }}" target="_blank" class="btn btn-sm btn-success ms-1">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada riwayat pengusulan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $historyPengusulan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengusul_content.css') }}">
@endpush