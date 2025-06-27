@extends('layouts.direktur.layout')

@section('title', 'Persetujuan Surat Tugas')
@section('direktur_content')
<div class="container-fluid">
    <h1 class="mb-4">Persetujuan Surat Tugas</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Menunggu Persetujuan Anda</h6>
            <form action="{{ route('direktur.persetujuan') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Pengajuan..." value="{{ request('search') }}">
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
                            <th>Pengusul</th>
                            <th>Wadir yang Memaraf</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Sumber Dana</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratTugasUntukReview as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($suratTugasUntukReview->currentPage() - 1) * $suratTugasUntukReview->perPage() }}</td>
                            <td>{{ $st->pengusul->name ?? 'N/A' }}</td>
                            <td>{{ $st->wadirApprover->name ?? 'N/A' }} ({{ $st->diusulkan_kepada }})</td>
                            <td>{{ $st->perihal_tugas }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d/m/Y') }} → {{ $st->tanggal_kembali->format('d/m/Y') }}</td>
                            <td>{{ $st->sumber_dana }}</td>
                            <td>
                                <a href="{{ route('direktur.review.surat_tugas', $st->surat_tugas_id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Review
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengajuan yang menunggu persetujuan Anda.</td>
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
</div>
@endsection