{{-- resources/views/paraf/index.blade.php --}}
@extends('layouts.main')

{{-- Menyertakan sidebar yang sesuai --}}
@section('sidebar')
    @php
        $userRole = null;
        $roleDisplayName = 'Pengguna';
        if (\Auth::check()) {
            $userRole = \Auth::user()->role;
            $loginController = new \App\Http\Controllers\Auth\LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }
    @endphp
    @if ($userRole)
        @if (in_array($userRole, ['wadir_1', 'wadir_2', 'wadir_3', 'wadir_4']))
            @include('layouts.Wadir.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'direktur')
            @include('direktur.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pelaksana')
            @include('layouts.pelaksana.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pengusul')
            @include('layouts.pengusul.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'admin')
            @include('layouts.admin.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'bku')
            @include('layouts.bku.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @else
            <div></div>
        @endif
    @else
        <div></div>
    @endif
@endsection

@section('content')
<div class="container-fluid paraf-page-container">
    <h1 class="page-title paraf-title mb-4">Paraf</h1>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show global-alert-app-top" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show global-alert-app-top" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show global-alert-app-top" role="alert">
            Terjadi kesalahan validasi. Mohon periksa kembali input Anda.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (!$hasDocuments)
    {{-- Tampilan Default: Belum ada dokumen --}}
    <div class="paraf-no-document-card card shadow mb-4">
        <div class="card-body text-center py-5">
            <img src="{{ asset('img/no-data.png') }}" alt="Upload Icon" class="paraf-no-document-icon mb-3"> {{-- Icon dari public/img/ --}}
            <p class="paraf-no-document-text mb-2">Belum ada file</p>
            <a href="#" class="paraf-upload-link" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload File</a> {{-- Link memicu modal --}}
        </div>
    </div>
    @else
    {{-- Tampilan Dokumen yang Sudah Diupload (Grid Card) --}}
    <div class="row paraf-documents-grid g-4"> {{-- Grid untuk card dokumen --}}
        @foreach ($documents as $doc)
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12"> {{-- Kolom responsif untuk setiap card --}}
            <div class="document-card card shadow h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    <img src="{{ asset('img/document_icon.png') }}" alt="Document Icon" class="document-card-icon mb-3"> {{-- Icon dokumen --}}
                    <h5 class="document-card-title mb-1">{{ $doc['file_name'] }}</h5>
                    <p class="document-card-info mb-1">Diunggah: {{ $doc['uploaded_at'] }}</p>
                    <p class="document-card-info mb-3">Oleh: {{ $doc['uploaded_by'] }}</p>
                    <span class="badge document-status-badge 
                        @if($doc['status'] == 'Menunggu Paraf') bg-warning text-dark
                        @elseif($doc['status'] == 'Sudah Diparaf') bg-success
                        @elseif($doc['status'] == 'Ditolak') bg-danger
                        @else bg-secondary @endif">
                        {{ $doc['status'] }}
                    </span>
                    <div class="document-actions mt-3">
                        <button class="btn btn-primary btn-sm me-2">Lihat</button>
                        @if($doc['status'] == 'Menunggu Paraf')
                            <button class="btn btn-success btn-sm">Paraf</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- Modal Upload File (DITAMBAHKAN DI SINI) --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Untuk Paraf</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('paraf.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_file" class="form-label">Pilih Dokumen (PDF, DOC, DOCX, maks 5MB)</label>
                        <input class="form-control" type="file" id="document_file" name="document_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/paraf.css') }}">
@endpush

@push('scripts')
    {{-- JS untuk halaman paraf (jika ada) --}}
@endpush