@extends('layouts.bku.layout')

@section('title', 'Laporan & Bukti Perjalanan Dinas')

@section('bku_content')
<div class="bku-container px-4 py-3">
    <h1 class="bku-page-title mb-4">Laporan & Bukti Perjalanan Dinas</h1>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Daftar Laporan & Bukti</h5>

        {{-- Search --}}
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('bku.laporan') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2"
                    placeholder="Cari Kegiatan..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Pengusulan</th>
                        <th>Pelaksanaan</th>
                        <th>Nomor Surat Tugas</th>
                        <th>Status Laporan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarTugas as $index => $tugas)
                    <tr>
                        <td>{{ $loop->iteration + ($daftarTugas->currentPage() - 1) * $daftarTugas->perPage() }}</td>
                        <td>{{ $tugas->perihal_tugas }}</td>
                        <td>{{ $tugas->created_at->format('d M Y') }}</td>
                        <td>{{ $tugas->tanggal_berangkat->format('d M Y') }} → {{ $tugas->tanggal_kembali->format('d M Y') }}</td>
                        <td>{{ $tugas->nomor_surat ?? '-' }}</td>
                        <td>
                            @if($tugas->laporanPerjalananDinas)
                                @php
                                    $status = $tugas->laporanPerjalananDinas->status_laporan;
                                @endphp

                                @if($status === 'Diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($status === 'Dikembalikan')
                                    <span class="badge bg-warning text-dark">Dikembalikan</span>
                                @else
                                    <span class="badge bg-warning text-dark">Dikembalikan</span>
                                @endif
                            @else
                                <span class="badge bg-danger">Belum Upload</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('bku.lihatLaporan', $tugas->surat_tugas_id) }}"
                                class="btn btn-info btn-sm mb-1">
                                <i class="fas fa-eye"></i> Lihat Bukti
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada laporan & bukti perjalanan dinas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3">
            {{ $daftarTugas->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/bku_content.css') }}">
@endpush
