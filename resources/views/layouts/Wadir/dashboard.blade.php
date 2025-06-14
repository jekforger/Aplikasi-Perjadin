{{-- resources/views/wadir/dashboard.blade.php --}}
@extends('layouts.Wadir.layout')

@section('wadir_content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    {{-- Kotak Info Dashboard --}}
    <div class="row">
        {{-- Kotak Usulan Baru --}}
        <div class="col-xl-4 col-md-6 mb-4"> {{-- Menggunakan col-xl-4 karena ada 3 kolom utama --}}
            <div class="card border-left-info shadow h-100 py-2"> {{-- Warna border sesuai desain (info blue) --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> {{-- Warna teks info --}}
                                USULAN BARU
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['usulan_baru'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Dalam Proses (Direktur) --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2"> {{-- Warna border sesuai desain (warning yellow) --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"> {{-- Warna teks warning --}}
                                DALAM PROSES (DIREKTUR)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['dalam_proses_direktur'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kotak Bertugas --}}
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2"> {{-- Warna border sesuai desain (success green) --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1"> {{-- Warna teks success --}}
                                BERTUGAS
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['bertugas'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-running fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Pengusulan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">USULAN PERJALANAN DINAS</h6> {{-- Judul tabel diubah --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Berangkat</th>
                            <th>Tanggal Pulang</th>
                            <th>Pembiayaan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengusulanDetails as $detail)
                        <tr>
                            <td>{{ $detail['no'] }}</td>
                            <td>{{ $detail['tgl_berangkat'] }}</td>
                            <td>{{ $detail['tgl_pulang'] }}</td>
                            <td>{{ $detail['pembiayaan'] }}</td>
                            <td>
                                @if($detail['status'] == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($detail['status'] == 'Disetujui')
                                    <span class="badge bg-success">Disetujui</span>
                                @elseif($detail['status'] == 'Ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @elseif($detail['status'] == 'Selesai')
                                    <span class="badge bg-info text-white">Selesai</span> {{-- Tambahkan text-white untuk kontras --}}
                                @else
                                    <span class="badge bg-secondary">{{ $detail['status'] }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pengusulan.</td> {{-- Sesuaikan colspan --}}
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/wadir.css') }}">
@endpush