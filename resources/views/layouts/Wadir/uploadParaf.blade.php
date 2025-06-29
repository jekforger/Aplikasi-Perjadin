{{-- resources/views/layouts/Wadir/uploadParaf.blade.php --}}
@extends('layouts.Wadir.layout')

@section('title', 'Upload Paraf')
@section('wadir_content')
<div class="container-fluid">
    <h1 class="mb-4">Paraf Digital</h1>

    <div class="card shadow mb-4 p-4">
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

            @if ($currentParafPath)
                <div class="d-flex flex-column align-items-center mb-4">
                    <h5 class="text-primary mb-3">Paraf Anda Saat Ini:</h5>
                    @if (Str::endsWith($currentParafPath, '.pdf'))
                        <div class="p-3 border rounded text-center mb-3" style="width: 200px; height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                            <a href="{{ Storage::url($currentParafPath) }}" target="_blank" class="small text-truncate" style="max-width: 180px;">{{ basename($currentParafPath) }}</a>
                        </div>
                    @else
                        <img src="{{ Storage::url($currentParafPath) }}" alt="Paraf Digital" class="img-fluid border rounded mb-3" style="max-width: 200px; height: auto; max-height: 150px; object-fit: contain;">
                    @endif

                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" id="deleteParafBtn">Hapus</a></li>
                            <li><a class="dropdown-item" href="#" id="uploadNewParafBtn">Upload Baru</a></li>
                        </ul>
                    </div>
                </div>
            @endif

            <div id="parafUploadFormContainer" style="{{ $currentParafPath ? 'display: none;' : '' }}">
                <h5 class="text-primary mb-3">{{ $currentParafPath ? 'Upload Paraf Baru:' : 'Unggah Paraf Digital Anda:' }}</h5>
                <form id="uploadParafForm" action="{{ route('wadir.paraf.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3 border border-dashed rounded p-4 text-center">
                        <label for="paraf_file" class="form-label d-block cursor-pointer">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i><br>
                            <span class="text-muted">Tarik dan lepaskan file di sini atau </span>
                            <span class="text-primary fw-bold">cari</span>
                        </label>
                        <input type="file" class="form-control d-none" id="paraf_file" name="paraf_file" accept=".pdf,image/png,image/jpeg,image/jpg">
                        <p class="text-muted small mt-2">Hanya mendukung file dengan format .pdf, .png, .jpg, .jpeg (maks 1MB).</p>
                        @error('paraf_file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <div id="selectedFilePreview" class="mt-3" style="display:none;">
                            <p class="mb-1">File terpilih:</p>
                            <span id="selectedFileName" class="d-inline-flex align-items-center"></span>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="clearSelectedFileBtn">
                                <i class="fas fa-times-circle"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" id="cancelUploadBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>

                {{-- Signature Pad --}}
                <div class="text-center my-4">
                    <span class="text-muted">Atau gunakan Signature Pad di bawah ini</span>
                    <hr>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tanda Tangan Digital (Signature Pad)</label>
                    <canvas id="signature-pad" class="border border-dark rounded w-100" style="height: 250px;"></canvas>
                    <div class="mt-2 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-danger btn-sm" id="clear-signature">Bersihkan</button>
                        <button type="button" class="btn btn-success btn-sm" id="save-signature">Simpan dari Signature Pad</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parafUploadFormContainer = document.getElementById('parafUploadFormContainer');
        const uploadNewParafBtn = document.getElementById('uploadNewParafBtn');
        const deleteParafBtn = document.getElementById('deleteParafBtn');
        const parafFileInput = document.getElementById('paraf_file');
        const selectedFilePreview = document.getElementById('selectedFilePreview');
        const selectedFileNameSpan = document.getElementById('selectedFileName');
        const clearSelectedFileBtn = document.getElementById('clearSelectedFileBtn');
        const cancelUploadBtn = document.getElementById('cancelUploadBtn');
        const uploadParafForm = document.getElementById('uploadParafForm');

        if (uploadNewParafBtn) {
            uploadNewParafBtn.addEventListener('click', function(e) {
                e.preventDefault();
                parafUploadFormContainer.style.display = 'block';
            });
        }

        if (cancelUploadBtn) {
            cancelUploadBtn.addEventListener('click', function() {
                parafUploadFormContainer.style.display = 'none';
                parafFileInput.value = '';
                selectedFilePreview.style.display = 'none';
            });
        }

        if (parafFileInput) {
            parafFileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    selectedFileNameSpan.innerHTML = `
                        <i class="fas fa-file-alt me-2"></i> ${file.name} (${(file.size / 1024 / 1024).toFixed(2)}MB)
                    `;
                    selectedFilePreview.style.display = 'block';
                } else {
                    selectedFilePreview.style.display = 'none';
                }
            });
        }

        if (clearSelectedFileBtn) {
            clearSelectedFileBtn.addEventListener('click', function() {
                parafFileInput.value = '';
                selectedFilePreview.style.display = 'none';
            });
        }

        if (uploadParafForm) {
            uploadParafForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.ok ? response.json() : response.json().then(err => { throw err }))
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        let errorMessage = data.message || 'Terjadi kesalahan.';
                        if (data.errors) {
                            errorMessage += '<ul>';
                            for (let key in data.errors) {
                                errorMessage += `<li>${data.errors[key].join(', ')}</li>`;
                            }
                            errorMessage += '</ul>';
                        }
                        Swal.fire('Gagal!', errorMessage, 'error');
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    Swal.fire('Error Jaringan!', 'Terjadi kesalahan koneksi.', 'error');
                });
            });
        }

        if (deleteParafBtn) {
            deleteParafBtn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin menghapus paraf?',
                    text: "File paraf Anda akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch("{{ route('wadir.paraf.delete') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Dihapus!', data.message, 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Delete Fetch Error:', error);
                            Swal.fire('Error Jaringan!', 'Terjadi kesalahan koneksi.', 'error');
                        });
                    }
                });
            });
        }

        // === Signature Pad ===
        const canvas = document.getElementById("signature-pad");
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: "rgb(255,255,255)",
            penColor: "black"
        });

        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.addEventListener("resize", resizeCanvas);
        resizeCanvas();

        document.getElementById("clear-signature").addEventListener("click", () => signaturePad.clear());

        document.getElementById("save-signature").addEventListener("click", () => {
            if (signaturePad.isEmpty()) {
                Swal.fire('Kosong!', 'Silakan tanda tangani terlebih dahulu.', 'warning');
                return;
            }

            const dataURL = signaturePad.toDataURL("image/png");

            fetch("{{ route('wadir.paraf.upload') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ signature: dataURL })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Gagal mengirim data tanda tangan.', 'error');
            });
        });
    });
</script>
@endpush
