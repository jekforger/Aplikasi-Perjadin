{{-- resources/views/pelaksana/dashboard.blade.php --}}
@extends('layouts.pelaksana.layout') {{-- Mengextend layout khusus Pelaksana --}}

@section('pelaksana_content') {{-- Memasukkan konten ke yield 'pelaksana_content' --}}
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    {{-- Kotak Info Dashboard --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4"> {{-- Ubah col-xl-3 menjadi col-xl-4 karena ada 3 kotak --}}
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Penugasan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['total_penugasan'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-check fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Penugasan Baru
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['penugasan_baru'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-plus-square fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2"> {{-- Warna border disesuaikan --}}
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"> {{-- Warna teks disesuaikan --}}
                                Laporan Belum Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dashboardStats['laporan_belum_selesai'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i> {{-- Icon disesuaikan --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Detail Tugas Pelaksana --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detail Penugasan Perjalanan Dinas</h6> {{-- Judul disesuaikan --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengusulan</th> {{-- Kolom disesuaikan --}}
                            <th>Tanggal Berangkat</th>
                            <th>Nomor Surat Tugas</th>
                            <th>Sumber Dana</th>
                            <th>Status Laporan</th> {{-- Kolom disesuaikan --}}
                            <th>Tanggungan Biaya</th> {{-- Kolom disesuaikan --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tugasDetails as $detail) {{-- Loop melalui $tugasDetails --}}
                        <tr>
                            <td>{{ $detail['no'] }}</td>
                            <td>{{ $detail['tanggal_pengusulan'] }}</td>
                            <td>{{ $detail['tanggal_berangkat'] }}</td>
                            <td>{{ $detail['nomor_surat_tugas'] }}</td>
                            <td>{{ $detail['sumber_dana'] }}</td>
                            <td>
                                @if($detail['status_laporan'] == 'Belum Upload')
                                    <span class="badge bg-danger text-dark">Belum Upload</span> {{-- Warna badge disesuaikan --}}
                                @elseif($detail['status_laporan'] == 'Selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($detail['status_laporan'] == 'Pending Revisi')
                                    <span class="badge bg-warning text-dark">Pending Revisi</span>
                                @else
                                    <span class="badge bg-secondary">{{ $detail['status_laporan'] }}</span>
                                @endif
                            </td>
                            <td>{{ $detail['tanggungan_biaya'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data penugasan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Bagian pagination/rows per page --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Rows per page: 
                    <select class="form-select form-select-sm d-inline-block w-auto">
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                    </select>
                </div>
                <div>
                    <span class="me-2">1 - 10 of {{ count($tugasDetails) }}</span>
                    <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-left"></i></button>
                    <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pelaksana.css') }}"> {{-- Panggil CSS khusus Pelaksana --}}
@endpush