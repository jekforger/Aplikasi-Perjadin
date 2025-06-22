@extends('layouts.pengusul.layout')

@section('title', 'Pengusulan')
@section('pengusul_content')
<div class="pengusul-container px-4 py-3">
    <h1 class="pengusul-page-title mb-4">Pengusulan</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <form id="pengusulanForm" action="{{ route('pengusul.store.pengusulan') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div id="step-1-form" class="form-step active-step">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3 mt-4">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan *</label>
                            <textarea class="form-control" id="nama_kegiatan" name="nama_kegiatan" rows="3" placeholder="Nama Kegiatan" required>{{ old('nama_kegiatan') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan *</label>
                            <textarea class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" rows="3" placeholder="Tempat Kegiatan" required>{{ old('tempat_kegiatan') }}</textarea>
                        </div>

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

                        <div class="form-section mb-4">
                            <label for="surat_undangan" class="form-label">Surat Undangan (Jika ada)</label>
                            <input type="file" class="form-control w-100" name="surat_undangan" id="surat_undangan" accept=".pdf,.jpg,.png,.doc,.docx">
                        </div>

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
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3 mt-4">
                            <label for="ditugaskan_sebagai" class="form-label">Ditugaskan Sebagai *</label>
                            <input type="text" class="form-control" id="ditugaskan_sebagai" name="ditugaskan_sebagai" placeholder="Ditugaskan Sebagai" required value="{{ old('ditugaskan_sebagai') }}">
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_pelaksanaan" class="form-label">Tanggal Pelaksanaan *</label>
                            <input
                            type="text" {{-- Changed to text for Flatpickr --}}
                            id="tanggal_pelaksanaan"
                            name="tanggal_pelaksanaan"
                            class="form-control"
                            placeholder="Tgl Berangkat → Tgl Pulang"
                            readonly
                            required
                            value="{{ old('tanggal_pelaksanaan') }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pagu</label>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="pagu_desentralisasi_checkbox" name="pagu_desentralisasi" {{ old('pagu_desentralisasi') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pagu_desentralisasi_checkbox">
                                Desentralisasi
                            </label>
                            </div>
                        </div>

                        <div class="mb-3" id="pagu_nominal_input_group" style="{{ old('pagu_desentralisasi') ? '' : 'display:none;' }}">
                            <label for="pagu_nominal" class="form-label">Nominal Pagu</label>
                            <input type="number" class="form-control" id="pagu_nominal" name="pagu_nominal" placeholder="Contoh: 1500000" value="{{ old('pagu_nominal') }}">
                        </div>

                        <div class="mb-3 mt-4">
                            <label for="alamat_kegiatan" class="form-label">Alamat Kegiatan *</label>
                            <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan" required>{{ old('alamat_kegiatan') }}</textarea>
                        </div>
                        
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

                        <div class="mb-3">
                            <label for="nomor_surat_usulan" class="form-label">Nomor Surat Usulan *</label>
                            <input type="text" class="form-control" id="nomor_surat_usulan" name="nomor_surat_usulan" placeholder="Nomor Surat Usulan" required value="{{ old('nomor_surat_usulan') }}">
                        </div>
                    </div>
                </div>
                <div class="text-start mt-4">
                    <button type="button" class="btn btn-primary" id="next-step-1">Selanjutnya</button>
                </div>
            </div> <div id="step-2-personnel" class="form-step" style="display: none;"> {{-- ID untuk langkah 2, disembunyikan --}}
                <h3 class="form-step-title">Data Personel</h3>
                <p>*Centang untuk memilih pegawai/mahasiswa yang akan ditugaskan!</p>

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="data-selection-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                Pilih Data
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="data-selection-dropdown">
                                <li><a class="dropdown-item" href="#" data-value="data-pegawai">Data Pegawai</a></li>
                                <li><a class="dropdown-item" href="#" data-value="data-mahasiswa">Data Mahasiswa</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control me-2" id="search-input" placeholder="Search" style="max-width: 200px;">
                        <button class="btn btn-primary" id="search-button">Search</button>
                    </div>
                </div>

                {{-- TABEL PEGAWAI --}}
                <div class="table-responsive" id="data-pegawai-table">
                    <table class="table table-striped" id="pegawaiTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-pegawai"></th>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Pangkat</th>
                                <th>Golongan</th>
                                <th>Jabatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pegawais as $index => $pegawai)
                            <tr>
                                <td>
                                    <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}"
                                        class="personel-checkbox"
                                        data-id="{{ $pegawai->id }}"
                                        data-type="pegawai"
                                        data-nama="{{ $pegawai->nama }}"
                                        data-nip="{{ $pegawai->nip ?? '-' }}"
                                        data-pangkat="{{ $pegawai->pangkat ?? '-' }}"
                                        data-golongan="{{ $pegawai->golongan ?? '-' }}"
                                        data-jabatan="{{ $pegawai->jabatan ?? '-' }}"
                                        data-jurusan=""
                                        data-prodi=""
                                        onchange="updateSelectedPersonel(this)">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pegawai->nama }}</td>
                                <td>{{ $pegawai->nip ?? '-'}}</td>
                                <td>{{ $pegawai->pangkat ?? '-' }}</td>
                                <td>{{ $pegawai->golongan ?? '-' }}</td>
                                <td>{{ $pegawai->jabatan ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data pegawai.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TABEL MAHASISWA --}}
                <div class="table-responsive" id="data-mahasiswa-table" style="display: none;">
                    <table class="table table-striped" id="mahasiswaTable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all-mahasiswa"></th>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>Prodi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mahasiswa as $index => $mhs)
                            <tr>
                                <td>
                                    <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}"
                                        class="personel-checkbox"
                                        data-id="{{ $mhs->id }}"
                                        data-type="mahasiswa"
                                        data-nama="{{ $mhs->nama }}"
                                        data-nip=""
                                        data-pangkat=""
                                        data-golongan=""
                                        data-jabatan=""
                                        data-nim="{{ $mhs->nim ?? '-' }}"
                                        data-jurusan="{{ $mhs->jurusan ?? '-' }}"
                                        data-prodi="{{ $mhs->prodi ?? '-' }}"
                                        onchange="updateSelectedPersonel(this)">
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>{{ $mhs->nim ?? '-'}}</td>
                                <td>{{ $mhs->jurusan ?? '-' }}</td>
                                <td>{{ $mhs->prodi ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data mahasiswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- TABEL PERSONEL TERPILIH (gabungan dari Pegawai dan Mahasiswa) --}}
                <div class="mt-4" id="selectedPersonelContainer" style="display: none;">
                    <h5 class="mb-3">Personel Terpilih:</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="selectedPersonelTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP/NIM</th>
                                    <th>Jabatan/Jurusan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="selectedPersonelList">
                                </tbody>
                        </table>
                    </div>
                </div>

                <div class="button-next mt-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="prev-step-2">Kembali</button>
                    <button type="button" class="btn btn-primary" id="next-step-2">Buat Surat Tugas</button>
                    <button type="button" class="btn btn-warning" id="save-draft">Simpan Draft</button>
                </div>
            </div> <div id="step-3-surat-tugas" class="form-step" style="display: none;"> {{-- ID untuk langkah 3, disembunyikan --}}
                <h3 class="form-step-title">Preview Surat Tugas</h3>
                <div class="document-container surat-tugas-body">
                    <div class="surat-tugas-header">
                    <img src="{{ asset('img/polban.png') }}" alt="POLBAN Logo" />
                    <div class="surat-tugas-header-text">
                        <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS,<br>DAN TEKNOLOGI</h1>
                        <h2>POLITEKNIK NEGERI BANDUNG</h2>
                        <p>Jalan Gegerkalong Hilir, Desa Ciwaruga, Bandung 40012, Kotak Pos 1234,</p>
                        <p>Telepon: (022) 2013789, Faksimile: (022) 2013889</p>
                        <p>Laman: <a href="https://www.polban.ac.id" target="_blank">www.polban.ac.id</a>,
                        Pos Elektronik: polban@polban.ac.id</p>
                    </div>
                    </div>
                    <hr class="surat-tugas-header-line" />

                    <div class="surat-tugas-content">

                    <div class="surat-tugas-title-wrapper">
                        <div class="surat-tugas-title-inner">
                        <h3>SURAT TUGAS</h3>
                        <p class="nomor">Nomor: <span id="nomor_surat_display"></span></p>
                        </div>
                    </div>

                    <p style="margin-bottom: 10px;">
                        Direktur memberi tugas kepada:
                    </p>

                    <div id="daftar_personel_surat_tugas" style="margin-bottom: 15px;">
                        {{-- Konten dinamis daftar personel akan diisi oleh JavaScript --}}
                    </div>


                    <p style="margin-top: 20px; margin-bottom: 10px;">
                        Untuk mengikuti kegiatan <span id="nama_kegiatan_display_text" class="fw-bold"></span>, diselenggarakan oleh <span id="penyelenggara_display" class="fw-bold"></span> pada:
                    </p>

                    <div class="surat-tugas-detail-row">
                        <div class="surat-tugas-detail-label">Hari/tanggal</div>
                        <div class="surat-tugas-detail-separator">:</div>
                        <div class="surat-tugas-detail-value"><span id="tanggal_pelaksanaan_display"></span></div>
                    </div>
                    <div class="surat-tugas-detail-row">
                        <div class="surat-tugas-detail-label">Tempat</div>
                        <div class="surat-tugas-detail-separator">:</div>
                        <div class="surat-tugas-detail-value">
                        <span id="tempat_kegiatan_display"></span>
                        <div id="alamat_kegiatan_display_detail" style="margin-left: 0;"></div>
                        </div>
                    </div>
                    <div class="surat-tugas-detail-row">
                        <div class="surat-tugas-detail-label">Kegiatan</div>
                        <div class="surat-tugas-detail-separator">:</div>
                        <div class="surat-tugas-detail-value"><span id="ditugaskan_sebagai_display"></span></div>
                    </div>


                    <p style="margin-top: 20px;">
                        Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.
                    </p>

                    <div class="surat-tugas-footer-wrapper">
                        <div class="surat-tugas-date-block">
                        <p class="date"><span id="tanggal_surat_display"></span></p>
                        <p class="ditandatangani-oleh">Direktur,</p>
                        </div>
                        <div class="surat-tugas-tembusan-label">
                        <p>Tembusan:</p>
                        </div>
                        <div class="surat-tugas-tembusan-list">
                        <ol>
                            <li>Para Wakil Direktur</li>
                            <li>Ketua Jurusan</li>
                        </ol>
                        </div>
                        <div class="surat-tugas-signature-block">
                        <div style="height: 60px;"></div>
                        <p>Maryani, S.E., M.Si., Ph.D.</p>
                        <p>NIP 196405041990032001</p>
                        </div>
                    </div>
                    </div>
                </div>
                {{-- Tombol Aksi untuk Surat Tugas --}}
                <div class="p-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" id="back-to-personnel">Kembali</button>
                    <button type="button" class="btn btn-primary" id="submit-pengusulan-final">Usulkan</button>
                </div>
            </div> </form>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengusulan.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="{{ asset('js/pengusulan.js') }}"></script>
@endpush