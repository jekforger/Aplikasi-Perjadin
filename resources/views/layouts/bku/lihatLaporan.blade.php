@extends('layouts.bku.layout')

@section('title', 'Lihat Laporan & Bukti Perjalanan Dinas')

@section('bku_content')
<div class="pelaksana-container px-4 py-3">
    <h1 class="pelaksana-page-title mb-4">Lihat Laporan & Bukti Perjalanan Dinas</h1>

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

    {{-- Tombol Back --}}
    <a href="{{ route('bku.laporan') }}" class="btn btn-link mb-3">
        &larr; Kembali ke Daftar Bukti
    </a>

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Nomor Surat Tugas: {{ $suratTugas->nomor_surat ?? '-' }}</h5>

        @if ($lampiranList->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <p class="text-muted mb-0">Belum ada lampiran bukti perjalanan dinas.</p>
            </div>
        @else
            <div class="row">
                @foreach ($lampiranList as $lampiran)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">{{ $lampiran->jenis_dokumen ?? 'Lampiran' }}</h6>

                                @php
                                    $ext = pathinfo($lampiran->path_file, PATHINFO_EXTENSION);
                                    $url = Storage::url($lampiran->path_file);
                                @endphp

                                <div style="height: 300px; overflow: hidden;" class="mb-2">
                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $url }}" alt="Lampiran" class="rounded"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @elseif ($ext === 'pdf')
                                        <iframe src="{{ $url }}" width="100%" height="100%" class="rounded"></iframe>
                                    @else
                                        <p class="text-muted">Format tidak didukung.</p>
                                    @endif
                                </div>

                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm mb-1">Lihat File</a>
                                <a href="{{ $url }}" download class="btn btn-outline-secondary btn-sm mb-1">Download</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Validasi --}}
            <div class="mt-4 d-flex justify-content-end">
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#modalTerima">
                    <i class="fas fa-check"></i> Terima Laporan
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalKembalikan">
                    <i class="fas fa-undo"></i> Kembalikan Laporan
                </button>
            </div>

            {{-- Modal Terima --}}
            <div class="modal fade" id="modalTerima" tabindex="-1" aria-labelledby="modalTerimaLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('bku.terimaLaporan', $suratTugas->surat_tugas_id) }}">
                        @csrf
                        <div class="modal-content rounded shadow-sm">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Terima Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin <strong>menerima</strong> laporan ini?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Ya, Terima</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Kembalikan --}}
            <div class="modal fade" id="modalKembalikan" tabindex="-1" aria-labelledby="modalKembalikanLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <form method="POST" action="{{ route('bku.kembalikanLaporan', $suratTugas->surat_tugas_id) }}">
                        @csrf
                        <div class="modal-content rounded shadow-sm">
                            <div class="modal-header">
                                <h5 class="modal-title">Kembalikan Laporan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="catatan_verifikasi_bku" class="form-label">Alasan Pengembalian</label>
                                    <textarea name="catatan_verifikasi_bku"
                                        id="catatan_verifikasi_bku"
                                        rows="4"
                                        class="form-control @error('catatan_verifikasi_bku') is-invalid @enderror"
                                        required
                                        placeholder="Tuliskan alasan dikembalikan...">{{ old('catatan_verifikasi_bku') }}</textarea>
                                    @error('catatan_verifikasi_bku')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Kembalikan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pelaksana_content.css') }}">
@endpush
