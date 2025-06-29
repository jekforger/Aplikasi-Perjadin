@extends('layouts.pelaksana.layout')

@section('title', 'Laporan')
@section('pelaksana_content')
        <div class="pelaksana-container px-4 py-3">
            <h1 class="pelaksana-page-title mb-4">Laporan Perjalanan Dinas</h1>

            <div class="p-4 shadow-sm bg-white rounded">
                <h5 class="mb-3">Daftar Tugas Saya</h5>

                {{-- Search Bar --}}
                <div class="d-flex justify-content-end mb-3">
                    <form action="{{ route('pelaksana.bukti') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Tugas..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Pengusulan</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Nomor Surat Tugas</th>
                            <th>Sumber Dana</th>
                            <th>Aksi</th>
                            <th>Tanggungan Biaya</th>
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
                            <td>{{ $tugas->sumber_dana ?? '-' }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-success">
                                    <i class="fas fa-upload"></i> Upload Laporan
                                </a>
                            </td>
                            <td>{{ $tugas->tanggungan_biaya ?? '-' }}</td>                
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Anda belum memiliki penugasan.</td>
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
    <link rel="stylesheet" href="{{ asset('css/pelaksana_content.css') }}">
@endpush