@extends('layouts.pengusul.layout')

@section('title', 'Pengusulan')
@section('pengusul_content')
<div class="pengusul-container px-4 py-3">
    <h1 class="pengusul-page-title mb-4">Pengusulan</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <form id="pengusulanForm" action="{{ route('pengusul.store.pengusulan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Langkah 1: Informasi Dasar Kegiatan -->
            <div id="initial-form" class="form-step">
                <div class="row">
                    <!-- Form Bagian Kiri -->
                    <div class="col-md-6">
                        <!-- Nama Kegiatan -->
                        <div class="mb-3 mt-4">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan *</label>
                            <textarea class="form-control" id="nama_kegiatan" name="nama_kegiatan" rows="3" placeholder="Nama Kegiatan" required>{{ old('nama_kegiatan') }}</textarea>
                        </div>

                        <!-- Tempat Kegiatan -->
                        <div class="mb-3">
                            <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan *</label>
                            <textarea class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" rows="3" placeholder="Tempat Kegiatan" required>{{ old('tempat_kegiatan') }}</textarea>
                        </div>

                        <!-- Diusulkan Kepada -->
                        <div class="form-section mb-4">
                            <label for="diusulkan_kepada" class="form-label">Diusulkan Kepada *</label>
                            <div class="d-flex align-items-end gap-2">
                                <input
                                    type="text"
                                    class="form-control"
                                    id="diusulkan_kepada"
                                    name="diusulkan_kepada"
                                    placeholder="Diusulkan Kepada"
                                    readonly
                                    required
                                    value="{{ old('diusulkan_kepada') }}"
                                >
                                <div class="dropdown">
                                    <button
                                        class="btn btn-secondary dropdown-toggle"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >
                                        Pilih
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item pilih-option" data-target="diusulkan_kepada" data-value="Wakil Direktur I" href="#">Wakil Direktur I</a></li>
                                        <li><a class="dropdown-item pilih-option" data-target="diusulkan_kepada" data-value="Wakil Direktur II" href="#">Wakil Direktur II</a></li>
                                        <li><a class="dropdown-item pilih-option" data-target="diusulkan_kepada" data-value="Wakil Direktur III" href="#">Wakil Direktur III</a></li>
                                        <li><a class="dropdown-item pilih-option" data-target="diusulkan_kepada" data-value="Wakil Direktur IV" href="#">Wakil Direktur IV</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Surat Undangan -->
                        <div class="form-section mb-4">
                            <label for="surat_undangan" class="form-label">Surat Undangan (Jika ada)</label>
                            <input type="file" class="form-control w-100" name="surat_undangan" id="surat_undangan" accept=".pdf,.jpg,.png,.doc,.docx">
                        </div>

                        <!-- Pembiayaan -->
                        <div class="form-section mb-4">
                            <label for="pembiayaan" class="form-label">Pembiayaan *</label>
                            <input type="hidden" name="pembiayaan" id="pembiayaan_value" value="{{ old('pembiayaan', 'Polban') }}">
                            <div class="d-flex flex-row gap-3 flex-wrap">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_polban" value="Polban" {{ old('pembiayaan', 'Polban') == 'Polban' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pembiayaan_polban">Polban</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_penyelenggara" value="Penyelenggara" {{ old('pembiayaan') == 'Penyelenggara' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pembiayaan_penyelenggara">Penyelenggara</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_polban_penyelenggara" value="Polban dan Penyelenggara" {{ old('pembiayaan') == 'Polban dan Penyelenggara' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pembiayaan_polban_penyelenggara">Polban dan Penyelenggara</label>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="text-start mt-4">
                            <button type="submit" class="btn btn-primary">Selanjutnya</button>
                        </div>
                    </div>

                    <!-- Form Bagian Kanan -->
                    <div class="col-md-6">
                        <!-- Ditugaskan Sebagai -->
                        <div class="mb-3 mt-4">
                            <label for="ditugaskan_sebagai" class="form-label">Ditugaskan Sebagai *</label>
                            <input type="text" class="form-control" id="ditugaskan_sebagai" name="ditugaskan_sebagai" placeholder="Ditugaskan Sebagai" required>
                        </div>

                        <!-- Pilih Tanggal -->
                        <div class="mb-3">
                            <label for="tanggal_pelaksanaan" class="form-label">Tanggal Pelaksanaan *</label>
                            <input
                            type="text"
                            id="tanggal_pelaksanaan"
                            name="tanggal_pelaksanaan"
                            class="form-control"
                            placeholder="Tgl Berangkat → Tgl Pulang"
                            readonly
                            required
                            value="{{ old('tanggal_pelaksanaan') }}"
                            >
                        </div>

                        <!-- Pagu Desentralisasi -->
                        <div class="mb-3">
                            <label class="form-label">Pagu</label>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="pagu_desentralisasi_checkbox" name="pagu_desentralisasi" {{ old('pagu_desentralisasi') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pagu_desentralisasi_checkbox">
                                Desentralisasi
                            </label>
                            </div>
                        </div>

                        <!-- Nominal Pagu (Only visible if Desentralisasi is checked) -->
                        <div class="mb-3" id="pagu_nominal_input_group" style="{{ old('pagu_desentralisasi') ? '' : 'display:none;' }}">
                            <label for="pagu_nominal" class="form-label">Nominal Pagu</label>
                            <input type="number" class="form-control" id="pagu_nominal" name="pagu_nominal" placeholder="Contoh: 1500000" value="{{ old('pagu_nominal') }}">
                        </div>

                        <!-- Alamat Kegiatan -->
                        <div class="mb-3 mt-4">
                            <label for="alamat_kegiatan" class="form-label">Alamat Kegiatan *</label>
                            <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan" required>{{ old('alamat_kegiatan') }}</textarea>
                        </div>
                        
                        <!-- Provinsi -->
                        <div class="form-section mb-4">
                            <label for="provinsi" class="form-label">Provinsi *</label>
                            <div class="d-flex align-items-end gap-2">
                                <input
                                type="text"
                                class="form-control"
                                id="provinsi"
                                name="provinsi"
                                placeholder="Provinsi"
                                readonly
                                required
                                value="{{ old('provinsi') }}"
                                data-old="{{ old('provinsi') }}"
                                >
                                <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih
                                </button>
                                    <ul class="dropdown-menu" id="provinsi-dropdown">
                                        {{-- Akan diisi lewat JS --}}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Nomor Surat Usulan -->
                        <div class="mb-3">
                            <label for="nomor_surat_usulan" class="form-label">Nomor Surat Usulan *</label>
                            <input type="text" class="form-control" id="nomor_surat_usulan" name="nomor_surat_usulan" placeholder="Nomor Surat Usulan" required value="{{ old('nomor_surat_usulan') }}">
                        </div>
                    </div>  <!-- End Form -->
                </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengusulan.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/pengusulan.js') }}"></script>
@endpush
