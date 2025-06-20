@extends('layouts.pengusul.pagePengusul')

@section('content')

{{-- ESSENTIAL STYLES FOR FORM STEPS --}}
<style>
  .form-step {
    display: none;
  }
  .form-step-active {
    display: block;
  }
  .form-section .dropdown-menu {
      position: absolute;
      z-index: 1050;
  }
</style>

{{-- STYLING DARI TEMPLATE SURAT TUGAS BARU (Diperkuat) --}}
<style>
    /* ========================= */
    /*    GLOBAL & LAYOUT        */
    /* ========================= */
    .surat-tugas-body {
      font-family: 'Times New Roman', serif;
    }
    .document-container {
      /* Ubah width agar lebih responsif di dalam kontainer web, tapi tetap proporsional */
      width: 100%; /* Ambil lebar penuh dari parent .card */
      max-width: 21cm; /* Batasi maksimal selebar A4 */
      min-height: 29.7cm; /* Tinggi A4 */
      background-color: white;
      padding-top: 2.5cm;
      padding-right: 3cm;
      padding-bottom: 2.5cm;
      padding-left: 3cm;
      box-sizing: border-box;
      line-height: 1.5;
      /* margin: 20px auto; REMOVED, parent card/form-step will handle margin */
      margin: 0 auto; /* Tengah secara horizontal dalam parent */
      box-shadow: none; /* Hapus shadow jika parent .card sudah ada */
    }

    /* ========================= */
    /*       HEADER STYLES       */
    /* ========================= */
    .surat-tugas-header {
      display: grid;
      grid-template-columns: auto 1fr;
      gap: 20px;
      align-items: flex-start;
      margin-bottom: 10px;
    }
    .surat-tugas-header img {
      width: 80px;
      height: auto;
      align-self: flex-start;
    }
    .surat-tugas-header-text {
      text-align: center;
      font-size: 10pt;
      margin-top: 10px;
    }
    .surat-tugas-header-text h1 {
      font-size: 14pt;
      font-weight: bold;
      margin: 0;
      line-height: 1.1;
      text-transform: uppercase;
    }
    .surat-tugas-header-text h2 {
      font-size: 12pt;
      font-weight: bold;
      margin: 3px 0 0;
      line-height: 1.1;
      text-transform: uppercase;
    }
    .surat-tugas-header-text p {
      font-size: 9.5pt;
      margin: 1px 0;
      line-height: 1.4;
    }
    .surat-tugas-header-line {
      border: 0;
      border-top: 1px solid #000;
      margin-top: 0;
      margin-bottom: 20px;
    }

    /* ========================= */
    /*       CONTENT STYLES      */
    /* ========================= */
    .surat-tugas-content {
      font-size: 11pt;
      line-height: 1.6;
    }
    .surat-tugas-title-wrapper {
      text-align: center;
      margin-bottom: 10px;
    }
    .surat-tugas-title-inner {
      display: inline-block;
      text-align: left;
    }
    .surat-tugas-title-inner h3 {
      font-size: 13pt;
      font-weight: bold;
      margin: 0;
      line-height: 1.2;
      text-transform: uppercase;
    }
    .surat-tugas-title-inner .nomor {
      font-size: 11pt;
      margin: 2px 0 0;
      line-height: 1.2;
    }
    .surat-tugas-content p {
      margin-bottom: 10px;
      text-align: justify;
    }
    .surat-tugas-detail-row {
      display: flex !important; /* Pastikan selalu flex */
      margin-bottom: 4px;
    }
    .surat-tugas-detail-label {
      flex-basis: 150px !important; /* Paksa lebar dasar */
      min-width: 150px !important;  /* Paksa lebar minimum */
      text-align: left;
      color: black !important; /* Paksa warna teks hitam */
      white-space: nowrap; /* Mencegah wrap jika label panjang */
    }
    .surat-tugas-detail-separator {
      flex-basis: 10px !important; /* Paksa lebar pemisah */
      text-align: center;
      color: black !important; /* Paksa warna teks hitam */
    }
    .surat-tugas-detail-value {
      flex-grow: 1;
      text-align: left;
      color: black !important; /* Paksa warna teks hitam */
    }

    /* ========================= */
    /*   FOOTER‐SECTIONS (Grid)   */
    /* ========================= */
    .surat-tugas-footer-wrapper {
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-rows: auto auto auto;
      column-gap: 20px;
      margin-top: 40px;
      width: 100%;
    }
    .surat-tugas-date-block {
      grid-column: 2;
      grid-row: 1;
      text-align: left;
    }
    .surat-tugas-date-block .date {
      margin: 0;
      margin-bottom: 2px;
      font-size: 11pt;
      line-height: 1.2;
      color: black !important;
    }
    .surat-tugas-date-block .ditandatangani-oleh {
      margin: 0;
      font-size: 11pt;
      line-height: 1.2;
      color: black !important;
    }
    .surat-tugas-tembusan-label {
      grid-column: 1;
      grid-row: 2;
      font-size: 10pt;
      line-height: 1.5;
      margin-top: 10px;
      color: black !important;
    }
    .surat-tugas-tembusan-list {
      grid-column: 1;
      grid-row: 3;
      font-size: 10pt;
      line-height: 1.5;
      margin-top: 4px;
    }
    .surat-tugas-tembusan-list ol {
      margin: 0;
      padding-left: 20px;
      list-style-type: decimal;
    }
    .surat-tugas-tembusan-list li {
      margin-bottom: 2px;
      color: black !important;
    }
    .surat-tugas-signature-block {
      grid-column: 2;
      grid-row: 3;
      align-self: start;
      font-size: 11pt;
      line-height: 1.5;
      text-align: left;
    }
    .surat-tugas-signature-block p {
      margin: 0;
      color: black !important;
    }
    .surat-tugas-body a {
      color: black !important; /* Paksa warna link hitam */
      text-decoration: none;
    }

    /* Style tambahan untuk tabel personel di preview surat */
    .surat-tugas-personel-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .surat-tugas-personel-table th,
    .surat-tugas-personel-table td {
        border: 1px solid #000; /* Border hitam untuk tabel */
        padding: 5px 8px;
        text-align: left;
        vertical-align: top;
        font-size: 10pt; /* Ukuran font lebih kecil untuk tabel personel */
        color: black !important;
    }
    .surat-tugas-personel-table th {
        background-color: #f2f2f2;
    }
</style>


<div class="card-container">
    <h2 class="page-title">Pengusulan</h2>
    <div class="card"> {{-- Ini adalah Bootstrap Card utama --}}
      <div class="label">
      </div>

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
              <input type="file" class="form-control" name="surat_undangan" id="surat_undangan" accept=".pdf,.jpg,.png,.doc,.docx">
            </div>

            <!-- Pembiayaan -->
            <div class="form-section mb-4">
              <label for="pembiayaan" class="form-label">Pembiayaan *</label>
              <input type="hidden" name="pembiayaan" id="pembiayaan_value" value="{{ old('pembiayaan', 'Polban') }}">
              <div class="d-flex flex-column gap-2">
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

          <!-- Form Bagian Kanan -->
          <div class="col-md-6">
            <!-- Ditugaskan Sebagai -->
            <div class="mb-3 mt-4">
                <label for="ditugaskan_sebagai" class="form-label">Ditugaskan Sebagai *</label>
                <input type="text" class="form-control" id="ditugaskan_sebagai" name="ditugaskan_sebagai" placeholder="Ditugaskan Sebagai" required value="{{ old('ditugaskan_sebagai') }}">
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
                  >
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Pilih
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Barat" href="#">Jawa Barat</a></li>
                      <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Tengah" href="#">Jawa Tengah</a></li>
                      <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Timur" href="#">Jawa Timur</a></li>
                      <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="DKI Jakarta" href="#">DKI Jakarta</a></li>
                      {{-- Add more provinces here --}}
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Nomor Surat Usulan -->
              <div class="mb-3">
                <label for="nomor_surat_usulan" class="form-label">Nomor Surat Usulan *</label>
                <input type="text" class="form-control" id="nomor_surat_usulan" name="nomor_surat_usulan" placeholder="Nomor Surat Usulan" required value="{{ old('nomor_surat_usulan') }}">
              </div>
            </div>
          </div>
          <!-- Button -->
          <div class="button-next mt-3">
            <button type="button" class="btn btn-primary" id="next-to-personel">Selanjutnya</button>
          </div>
        </div> <!-- End Initial Form -->

        <!-- Data Pegawai and Mahasiswa Section -->
        <div id="data-section" class="form-step">
            <h3>Data Personel</h3>
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
                            <!-- Daftar personel terpilih akan muncul di sini (dibuat oleh JS) -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="button-next mt-3">
                <button type="button" class="btn btn-primary" id="back">Kembali</button>
                <button type="button" class="btn btn-success" id="create-task">Buat Surat Tugas</button>
                <button type="button" class="btn btn-warning" id="save-draft">Simpan Draft</button>
            </div>
        </div> <!-- End Data Section -->

        <!-- Surat Tugas Section -->
        <div id="surat-tugas-section" class="form-step">
            <div class="document-container surat-tugas-body">
                <!-- =========== HEADER HALAMAN =========== -->
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

                <!-- =========== ISI UTAMA HALAMAN =========== -->
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

                  <!-- Detail Kegiatan -->
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

                  <!-- =========== FOOTER‐GRID (Tembusan & Signature) =========== -->
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
                <button type="button" class="btn btn-secondary" id="back-to-form">Kembali</button>
                <button type="button" class="btn btn-primary" id="submit-surat">Usulkan</button>
            </div>
        </div> <!-- End Surat Tugas Section -->

      </form> {{-- End Form --}}
    </div>
  </div>

@push('scripts')
<script>
    // Initial setup for form steps
    let currentStep = 1;
    const formSteps = document.querySelectorAll('.form-step');

    function showStep(stepNumber) {
        formSteps.forEach((step, index) => {
            if (index + 1 === stepNumber) {
                step.classList.add('form-step-active');
            } else {
                step.classList.remove('form-step-active');
            }
        });
        // Ensure correct table is shown and DataTables redraws if needed
        if (stepNumber === 2) {
            // Restore correct table visibility
            const selectedDropdownText = $('#data-selection-dropdown').text();
            if (selectedDropdownText.includes('Pegawai')) {
                $('#data-pegawai-table').show();
                $('#data-mahasiswa-table').hide();
            } else if (selectedDropdownText.includes('Mahasiswa')) {
                $('#data-mahasiswa-table').show();
                $('#data-pegawai-table').hide();
            } else {
                $('#data-pegawai-table').show();
                $('#data-mahasiswa-table').hide();
                $('#data-selection-dropdown').text('Data Pegawai');
            }
            if ($.fn.DataTable.isDataTable('#pegawaiTable')) $('#pegawaiTable').DataTable().columns.adjust().draw();
            if ($.fn.DataTable.isDataTable('#mahasiswaTable')) $('#mahasiswaTable').DataTable().columns.adjust().draw();
        }
    }

    $(document).ready(function() {
        showStep(1);

        if (!$.fn.DataTable.isDataTable('#pegawaiTable')) {
            $('#pegawaiTable').DataTable({ paging: true, searching: true, info: true, pageLength: 5, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
        } else {
             $('#pegawaiTable').DataTable().columns.adjust().draw();
        }

        if (!$.fn.DataTable.isDataTable('#mahasiswaTable')) {
            $('#mahasiswaTable').DataTable({ paging: true, searching: true, info: true, pageLength: 10, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
        } else {
             $('#mahasiswaTable').DataTable().columns.adjust().draw();
        }

        $('#selectedPersonelContainer').hide();

        $('#pagu_nominal_input_group').toggle($('#pagu_desentralisasi_checkbox').is(':checked'));

        $('input[name="pembiayaan_option"][value="{{ old('pembiayaan', 'Polban') }}"]').prop('checked', true);
        $('#pagu_desentralisasi_checkbox').prop('checked', {{ old('pagu_desentralisasi') ? 'true' : 'false' }});

        $('#pagu_nominal_input_group').toggle($('#pagu_desentralisasi_checkbox').is(':checked'));

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error Validasi!',
                html: 'Mohon periksa kembali input Anda:<br><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonText: 'Oke'
            });
            showStep(1);
        @endif
        initializeSelectedPersonel();
    });

    let selectedPersonel = [];

    function initializeSelectedPersonel() {
        selectedPersonel = [];
        $('.personel-checkbox:checked').each(function() {
            const checkbox = this;
            const personelData = {
                id: checkbox.dataset.id, type: checkbox.dataset.type, nama: checkbox.dataset.nama,
                nip: checkbox.dataset.nip, pangkat: checkbox.dataset.pangkat,
                golongan: checkbox.dataset.golongan, jabatan: checkbox.dataset.jabatan,
                nim: checkbox.dataset.nim, jurusan: checkbox.dataset.jurusan, prodi: checkbox.dataset.prodi,
            };
            selectedPersonel.push(personelData);
        });
        renderSelectedPersonel();
    }


    function updateSelectedPersonel(checkbox) {
        const personelId = checkbox.dataset.id;
        const type = checkbox.dataset.type;
        const personelData = {
            id: personelId, type: type, nama: checkbox.dataset.nama,
            nip: checkbox.dataset.nip, pangkat: checkbox.dataset.pangkat,
            golongan: checkbox.dataset.golongan, jabatan: checkbox.dataset.jabatan,
            nim: checkbox.dataset.nim, jurusan: checkbox.dataset.jurusan, prodi: checkbox.dataset.prodi,
        };
        if (checkbox.checked) {
            if (!selectedPersonel.some(p => p.id === personelId && p.type === type)) {
                selectedPersonel.push(personelData);
            }
        } else {
            selectedPersonel = selectedPersonel.filter(p => !(p.id === personelId && p.type === type));
            if (type === 'pegawai') $('#select-all-pegawai').prop('checked', false);
            else if (type === 'mahasiswa') $('#select-all-mahasiswa').prop('checked', false);
        }
        renderSelectedPersonel();
    }

    function renderSelectedPersonel() {
        const container = $('#selectedPersonelContainer');
        const listElement = $('#selectedPersonelList');
        listElement.html('');
        if (selectedPersonel.length > 0) {
            container.show();
            selectedPersonel.forEach((personel, index) => {
                let identifier = personel.type === 'pegawai' ? (personel.nip || '-') : (personel.type === 'mahasiswa' ? (personel.nim || '-') : '-');
                let roleOrMajor = personel.type === 'pegawai' ? (personel.jabatan || '-') : (personel.type === 'mahasiswa' ? (personel.jurusan || '-') : '-');
                const rowHtml = `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${personel.nama}</td>
                        <td>${identifier}</td>
                        <td>${roleOrMajor}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removePersonel('${personel.id}', '${personel.type}')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>`;
                listElement.append(rowHtml);
            });
        } else {
            container.hide();
        }
    }

    function removePersonel(personelId, type) {
        const checkbox = $(`input.personel-checkbox[data-id="${personelId}"][data-type="${type}"]`);
        if (checkbox.length) {
            checkbox.prop('checked', false);
            updateSelectedPersonel(checkbox[0]);
        }
    }

    $('#select-all-pegawai').on('click', function () {
        const isChecked = this.checked;
        $('input.personel-checkbox[data-type="pegawai"]').each(function() {
            if ($(this).prop('checked') !== isChecked) {
                $(this).prop('checked', isChecked);
                updateSelectedPersonel(this);
            }
        });
    });
    $('#select-all-mahasiswa').on('click', function () {
        const isChecked = this.checked;
        $('input.personel-checkbox[data-type="mahasiswa"]').each(function() {
            if ($(this).prop('checked') !== isChecked) {
                $(this).prop('checked', isChecked);
                updateSelectedPersonel(this);
            }
        });
    });

    $('#next-to-personel').on('click', function (e) {
        const form = document.getElementById('pengusulanForm');
        if (!form.reportValidity()) {
            Swal.fire('Error Validasi!', 'Mohon lengkapi semua field yang wajib diisi pada formulir pertama.', 'error');
            return;
        }
        currentStep = 2;
        showStep(currentStep);
    });

    $('#back').on('click', () => {
        currentStep = 1;
        showStep(currentStep);
    });

    $('#save-draft').on('click', function () {
         if (selectedPersonel.length === 0) {
            Swal.fire('Peringatan!','Pilih setidaknya satu personel untuk simpan draft!','warning');
            return;
        }
        const formData = new FormData(document.getElementById('pengusulanForm'));
        selectedPersonel.forEach(p => {
            if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
            else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
        });
        formData.append('status_pengajuan', 'draft');

        fetch("{{ route('pengusul.store.pengusulan') }}", {
            method: 'POST', body: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Draft Disimpan!', data.message || 'Draft berhasil disimpan.','success')
                .then(() => { /* No reload for draft, stay on page, maybe clear selection */ });
            } else {
                Swal.fire('Gagal!', data.message || 'Gagal menyimpan draft.','error');
            }
        }).catch(error => {
            console.error('Fetch Error:', error);
            let errorMessage = 'Terjadi kesalahan jaringan.';
            if (error.errors) {
                errorMessage = '<strong>Kesalahan Validasi:</strong><ul>';
                for (let key in error.errors) {
                    errorMessage += `<li>${error.errors[key].join(', ')}</li>`;
                }
                errorMessage += '</ul>';
            } else if (error.message) {
                errorMessage = error.message;
            }
            Swal.fire('Error!', errorMessage,'error');
        });
    });


    $('#create-task').on('click', function () {
        if (selectedPersonel.length === 0) {
            Swal.fire('Peringatan!', 'Pilih setidaknya satu personel!', 'warning'); return;
        }
        Swal.fire({
            title: 'Konfirmasi', text: 'Yakin untuk membuat surat tugas?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                currentStep = 3;
                showStep(currentStep);
                const form = $('#pengusulanForm');

                $('#nomor_surat_display').text(form.find('#nomor_surat_usulan').val() ? form.find('#nomor_surat_usulan').val() + '/PL12.C01/KP/' + new Date().getFullYear() : "___/PL12.C01/KP/" + new Date().getFullYear());
                $('#nama_kegiatan_display_text').text(form.find('#nama_kegiatan').val() || '-');
                let tempatKegiatanVal = form.find('#tempat_kegiatan').val();
                $('#penyelenggara_display').text(tempatKegiatanVal.split(',')[0] || 'Penyelenggara Kegiatan');
                $('#tanggal_pelaksanaan_display').text(form.find('#tanggal_pelaksanaan').val() || '-');
                $('#tempat_kegiatan_display').text(tempatKegiatanVal || '-');
                $('#alamat_kegiatan_display_detail').html((form.find('#alamat_kegiatan').val() || '-').replace(/\n/g, '<br>'));
                $('#ditugaskan_sebagai_display').text(form.find('#ditugaskan_sebagai').val() || '-');
                const today = new Date();
                $('#tanggal_surat_display').text(`Bandung, ${String(today.getDate()).padStart(2, '0')} ${["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"][today.getMonth()]} ${today.getFullYear()}`);

                const daftarPersonelContainer = $('#daftar_personel_surat_tugas');
                daftarPersonelContainer.html('');
                if (selectedPersonel.length > 0) {
                    let personelHtml = '';
                    selectedPersonel.forEach((personel) => {
                        personelHtml += `
                            <div style="margin-bottom: 15px;">
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Nama</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nama || '-'}</div>
                                </div>`;
                        if (personel.type === 'pegawai') {
                            personelHtml += `
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">NIP</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nip || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Pangkat/golongan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.pangkat || '-'} / ${personel.golongan || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Jabatan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.jabatan || '-'}</div>
                                </div>`;
                        } else if (personel.type === 'mahasiswa') {
                            personelHtml += `
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">NIM</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.nim || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Jurusan</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.jurusan || '-'}</div>
                                </div>
                                <div class="surat-tugas-detail-row">
                                    <div class="surat-tugas-detail-label">Program Studi</div>
                                    <div class="surat-tugas-detail-separator">:</div>
                                    <div class="surat-tugas-detail-value">${personel.prodi || '-'}</div>
                                </div>`;
                        }
                        personelHtml += `</div>`;
                    });
                    daftarPersonelContainer.append(personelHtml);
                } else {
                    daftarPersonelContainer.html('<p class="text-muted fst-italic">(Tidak ada personel yang dipilih)</p>');
                }
            }
        });
    });

    $('#back-to-form').on('click', () => { currentStep = 2; showStep(currentStep); });

    $('#submit-surat').on('click', function () {
         Swal.fire({
            title: 'Konfirmasi Pengusulan', text: 'Data sudah benar?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(document.getElementById('pengusulanForm'));
                selectedPersonel.forEach(p => {
                    if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
                    else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
                });
                formData.append('status_pengajuan', 'diajukan');

                fetch("{{ route('pengusul.store.pengusulan') }}", {
                    method: 'POST', body: formData,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message || 'Surat tugas berhasil diusulkan!','success')
                        .then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message || 'Gagal mengusulkan.','error');
                    }
                }).catch(error => {
                    console.error('Fetch Error:', error);
                    let errorMessage = 'Terjadi kesalahan jaringan.';
                    if (error.errors) {
                        errorMessage = '<strong>Kesalahan Validasi:</strong><ul>';
                        for (let key in error.errors) {
                            errorMessage += `<li>${error.errors[key].join(', ')}</li>`;
                        }
                        errorMessage += '</ul>';
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    Swal.fire('Error!', errorMessage,'error');
                });
            }
        });
    });

    $(document).on('click', '#data-section .dropdown-item[data-value]', function (e) {
        e.preventDefault();
        const section = $(this).data('value');
        $('#data-selection-dropdown').text($(this).text());

        $('#data-pegawai-table, #data-mahasiswa-table').hide();
        if (section === 'data-pegawai') {
            $('#data-pegawai-table').show();
            if ($.fn.DataTable.isDataTable('#pegawaiTable')) {
                $('#pegawaiTable').DataTable().columns.adjust().draw();
            }
        } else if (section === 'data-mahasiswa') {
            $('#data-mahasiswa-table').show();
             if ($.fn.DataTable.isDataTable('#mahasiswaTable')) {
                $('#mahasiswaTable').DataTable().columns.adjust().draw();
            }
        }
    });

    $('#search-input').on('keyup', function () {
        let tableAPI;
        if ($('#data-pegawai-table').is(':visible')) {
            tableAPI = $('#pegawaiTable').DataTable();
        } else if ($('#data-mahasiswa-table').is(':visible')) {
            tableAPI = $('#mahasiswaTable').DataTable();
        } else {
            return;
        }
        tableAPI.search(this.value).draw();
    });

    $('.pilih-option').on('click', function () {
        const targetInputId = $(this).data('target');
        const selectedValue = $(this).data('value');
        $('#' + targetInputId).val(selectedValue);
    });

    $('input[name="pembiayaan_option"]').on('change', function(){
        $('#pembiayaan_value').val($(this).val());
    });

    $('#pagu_desentralisasi_checkbox').on('change', function() {
        $('#pagu_nominal_input_group').toggle(this.checked);
        if (!this.checked) {
            $('#pagu_nominal').val('');
        }
    });

    $('#tanggal_pelaksanaan').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            format: 'DD/MM/YYYY'
        }
    })
    .on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' → ' + picker.endDate.format('DD/MM/YYYY'));
    })
    .on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
</script>
@endpush
@endsection