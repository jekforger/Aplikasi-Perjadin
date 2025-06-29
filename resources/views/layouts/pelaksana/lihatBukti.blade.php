@extends('layouts.pelaksana.layout')

@section('title', 'Lihat Bukti Perjalanan Dinas')

@section('pelaksana_content')
<div class="pelaksana-container px-4 py-3">
    <h1 class="pelaksana-page-title mb-4">Lihat Bukti Perjalanan Dinas</h1>

    {{-- Tombol Back --}}
    <a href="{{ route('pelaksana.bukti') }}" class="btn btn-link mb-3">
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

                                {{-- FIXED HEIGHT WRAPPER --}}
                                <div style="height: 300px; overflow: hidden;" class="mb-2">
                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $url }}" alt="Lampiran"
                                             style="width: 100%; height: 100%; object-fit: cover;"
                                             class="rounded">
                                    @elseif ($ext === 'pdf')
                                        <iframe src="{{ $url }}" width="100%" height="100%" class="rounded"></iframe>
                                    @else
                                        <p class="text-muted">Format tidak didukung.</p>
                                    @endif
                                </div>

                                {{-- Tombol Aksi --}}
                                <a href="{{ $url }}" target="_blank" class="btn btn-outline-primary btn-sm mb-1">
                                    Lihat File
                                </a>
                                <a href="{{ $url }}" download class="btn btn-outline-secondary btn-sm mb-1">
                                    Download
                                </a>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pelaksana.bukti.destroyLampiran', [
                                    'laporan_id' => $lampiran->laporan_id,
                                    'dokumen_lampiran_id' => $lampiran->dokumen_lampiran_id
                                ]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lampiran ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mt-2">
                                        Hapus
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pelaksana_content.css') }}">
@endpush
