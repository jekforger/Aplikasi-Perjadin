{{-- resources/views/layouts/Direktur/paraf.blade.php --}}
@extends('layouts.direktur.layout')

@section('title', 'Upload Tanda Tangan')
@section('direktur_content')
<div class="container-fluid">
    <h1 class="mb-4">Tanda Tangan Digital</h1>

    <div class="card shadow mb-4 p-4">
        <div class="card-header py-3 bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">Upload Dokumen Tanda Tangan</h6>
            <p class="text-muted small">Upload hasil scan tanda tangan dengan background transparan dalam format .png.</p>
        </div>
        <div class="card-body">
            @if(session('success_message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($currentSignaturePath)
                {{-- Tampilan jika Tanda Tangan sudah ada --}}
                <div class="d-flex flex-column align-items-center mb-4">
                    <h5 class="text-primary mb-3">Tanda Tangan Anda Saat Ini:</h5>
                    <img src="{{ Storage::url($currentSignaturePath) }}" alt="Tanda Tangan Digital" class="img-fluid border rounded mb-3" style="max-width: 250px; height: auto; object-fit: contain;">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" id="uploadNewSignatureBtn">Upload Baru</a></li>
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Form Upload (tersembunyi jika tanda tangan sudah ada) --}}
            <div id="signatureUploadFormContainer" style="{{ $currentSignaturePath ? 'display: none;' : '' }}">
                <h5 class="text-primary mb-3">{{ $currentSignaturePath ? 'Upload Tanda Tangan Baru:' : 'Unggah Tanda Tangan Anda:' }}</h5>
                <form id="uploadSignatureForm" action="{{ route('direktur.paraf.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 border border-dashed rounded p-4 text-center">
                        <label for="signature_file" class="form-label d-block cursor-pointer">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i><br>
                            <span class="text-muted">Tarik dan lepaskan file di sini atau </span>
                            <span class="text-primary fw-bold">cari</span>
                        </label>
                        <input type="file" class="form-control d-none" id="signature_file" name="signature_file" accept="image/png">
                        <p class="text-muted small mt-2">Hanya mendukung file dengan format .png (maks 1MB).</p>
                        @error('signature_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadNewSignatureBtn = document.getElementById('uploadNewSignatureBtn');
    const signatureUploadFormContainer = document.getElementById('signatureUploadFormContainer');

    if (uploadNewSignatureBtn) {
        uploadNewSignatureBtn.addEventListener('click', function(e) {
            e.preventDefault();
            signatureUploadFormContainer.style.display = 'block';
        });
    }
});
</script>
@endpush