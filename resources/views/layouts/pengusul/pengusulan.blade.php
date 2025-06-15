@extends('layouts.pengusul.pagePengusul')

@section('content')
<style>
  .form-step {
    display: none;
  }
  .form-step-active {
    display: block;
  }
</style>

<<<<<<< Updated upstream
<body>
  <div class="card-container">
=======
<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<!-- jQuery, Moment.js, dan Date Range Picker -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- STYLING DARI TEMPLATE SURAT TUGAS BARU --}}
<style>
    /* ========================= */
    /*    GLOBAL & LAYOUT        */
    /* ========================= */
    .surat-tugas-body { /* Mengganti body agar tidak bentrok dengan body utama halaman */
      font-family: 'Times New Roman', serif;
      /* Hapus margin dan padding default dari body surat, karena akan diatur oleh .document-container */
    }
    .document-container {
      width: 21cm;              /* Lebar A4 */
      min-height: 29.7cm;       /* Tinggi A4 - bisa juga di-set ke auto jika konten dinamis */
      background-color: white;
      padding-top: 2.5cm;
      padding-right: 3cm;
      padding-bottom: 2.5cm;
      padding-left: 3cm;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Diberikan oleh .card bawaan, jadi ini opsional */
      box-sizing: border-box;
      line-height: 1.5;
      margin: 20px auto; /* Agar ada jarak jika di dalam card */
    }

    /* ========================= */
    /*       HEADER STYLES       */
    /* ========================= */
    .surat-tugas-header { /* Ubah nama kelas agar lebih spesifik */
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
    .surat-tugas-header-text { /* Ubah nama kelas */
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
    .surat-tugas-header-line { /* Ubah nama kelas */
      border: 0;
      border-top: 1px solid #000;
      margin-top: 0;
      margin-bottom: 20px;
    }

    /* ========================= */
    /*       CONTENT STYLES      */
    /* ========================= */
    .surat-tugas-content { /* Ubah nama kelas */
      font-size: 11pt;
      line-height: 1.6;
    }
    .surat-tugas-title-wrapper { /* Ubah nama kelas */
      text-align: center;
      margin-bottom: 10px;
    }
    .surat-tugas-title-inner { /* Ubah nama kelas */
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
    .surat-tugas-detail-row { /* Ubah nama kelas */
      display: flex;
      margin-bottom: 4px;
    }
    .surat-tugas-detail-label { /* Ubah nama kelas */
      flex-basis: 150px; /* Disesuaikan agar label lebih panjang jika perlu */
      min-width: 150px;
      text-align: left;
    }
    .surat-tugas-detail-separator { /* Ubah nama kelas */
      flex-basis: 10px;
      text-align: center;
    }
    .surat-tugas-detail-value { /* Ubah nama kelas */
      flex-grow: 1;
      text-align: left;
    }

    /* ========================= */
    /*   FOOTER‐SECTIONS (Grid)   */
    /* ========================= */
    .surat-tugas-footer-wrapper { /* Ubah nama kelas */
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-rows: auto auto auto;
      column-gap: 20px;
      margin-top: 40px;
      width: 100%;
    }
    .surat-tugas-date-block { /* Ubah nama kelas */
      grid-column: 2;
      grid-row: 1;
      text-align: left;
    }
    .surat-tugas-date-block .date {
      margin: 0;
      margin-bottom: 2px;
      font-size: 11pt;
      line-height: 1.2;
    }
    .surat-tugas-date-block .ditandatangani-oleh {
      margin: 0;
      font-size: 11pt;
      line-height: 1.2;
    }
    .surat-tugas-tembusan-label { /* Ubah nama kelas */
      grid-column: 1;
      grid-row: 2;
      font-size: 10pt;
      line-height: 1.5;
      margin-top: 10px;
    }
    .surat-tugas-tembusan-list { /* Ubah nama kelas */
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
    }
    .surat-tugas-signature-block { /* Ubah nama kelas */
      grid-column: 2;
      grid-row: 3;
      align-self: start;
      font-size: 11pt;
      line-height: 1.5;
      text-align: left;
    }
    .surat-tugas-signature-block p {
      margin: 0;
    }
    .surat-tugas-body a { /* Ubah nama kelas */
      color: black;
      text-decoration: none;
    }
</style>
{{-- AKHIR STYLING DARI TEMPLATE SURAT TUGAS BARU --}}


<div class="card-container">
>>>>>>> Stashed changes
    <h2 class="page-title">Pengusulan</h2>
    <div class="card">
      <div class="label">
        <h6>*Semua form wajib di isi untuk keperluan data di dalam surat, terkecuali form "Pagu"</h6>
      </div>

      {{-- Tambahkan form tag disini --}}
      <form id="pengusulanForm" action="{{ route('pengusulan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf {{-- CSRF Token --}}

<<<<<<< Updated upstream
        <!-- Langkah 1: Informasi Dasar Kegiatan -->
        <div id="step-1" class="form-step form-step-active">
          <div class="row">
            <!-- Form Bagian Kiri -->
            <div class="col-md-6">
              <!-- Nama Kegiatan -->
            <div class="mb-3 mt-4">
              <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
              <textarea class="form-control" id="namaKegiatan" rows="3" placeholder="Nama Kegiatan"></textarea>
=======
            <div class="row">
                <!-- Form Bagian Kiri -->
                <div class="col-md-6">
                    <!-- Nama Kegiatan -->
                    <div class="mb-3 mt-4">
                        <label for="nama_kegiatan" class="form-label">Nama Kegiatan</label>
                        <textarea class="form-control" id="nama_kegiatan" name="nama_kegiatan" rows="3" placeholder="Nama Kegiatan" required></textarea>
                    </div>

                    <!-- Tempat Kegiatan -->
                    <div class="mb-3">
                        <label for="tempat_kegiatan" class="form-label">Tempat Kegiatan</label>
                        <textarea class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" rows="3" placeholder="Tempat Kegiatan" required></textarea>
                    </div>

                    <!-- Diusulkan Kepada -->
                    <div class="form-section mb-4">
                        <label for="diusulkan_kepada" class="form-label">Diusulkan Kepada</label>
                        <div class="d-flex align-items-end gap-2">
                            <input type="text" class="form-control" id="diusulkan_kepada" name="diusulkan_kepada" placeholder="Diusulkan Kepada" readonly required>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                        <label for="pembiayaan" class="form-label">Pembiayaan</label>
                        <input type="hidden" name="pembiayaan" id="pembiayaan_value" value="Polban"> {{-- Default Polban --}}
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_polban" value="Polban" checked>
                                <label class="form-check-label" for="pembiayaan_polban">Polban</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_penyelenggara" value="Penyelenggara">
                                <label class="form-check-label" for="pembiayaan_penyelenggara">Penyelenggara</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="pembiayaan_option" id="pembiayaan_polban_penyelenggara" value="Polban dan Penyelenggara">
                                <label class="form-check-label" for="pembiayaan_polban_penyelenggara">Polban dan Penyelenggara</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Bagian Kanan -->
                <div class="col-md-6">
                    <!-- Ditugaskan Sebagai -->
                    <div class="mb-3 mt-4">
                        <label for="ditugaskan_sebagai" class="form-label">Ditugaskan Sebagai</label>
                        <input type="text" class="form-control" id="ditugaskan_sebagai" name="ditugaskan_sebagai" placeholder="Ditugaskan Sebagai" required>
                    </div>

                    <!-- Tanggal Pelaksanaan -->
                    <div class="mb-3">
                        <label for="tanggal_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                        <input type="text" id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" class="form-control" placeholder="Tgl Berangkat → Tgl Pulang" readonly required>
                    </div>

                    <!-- Pagu Desentralisasi -->
                    <div class="mb-3">
                        <label class="form-label">Pagu</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="pagu_desentralisasi_checkbox" name="pagu_desentralisasi">
                            <label class="form-check-label" for="pagu_desentralisasi_checkbox">
                                Desentralisasi
                            </label>
                        </div>
                    </div>

                    <!-- Alamat Kegiatan -->
                    <div class="mb-3 mt-4">
                        <label for="alamat_kegiatan" class="form-label">Alamat Kegiatan</label>
                        <textarea class="form-control" id="alamat_kegiatan" name="alamat_kegiatan" rows="3" placeholder="Alamat Kegiatan" required></textarea>
                    </div>

                    <!-- Provinsi -->
                    <div class="form-section mb-4">
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <div class="d-flex align-items-end gap-2">
                            <input type="text" class="form-control" id="provinsi" name="provinsi" placeholder="Provinsi" readonly required>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Barat" href="#">Jawa Barat</a></li>
                                    <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Tengah" href="#">Jawa Tengah</a></li>
                                    <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="Jawa Timur" href="#">Jawa Timur</a></li>
                                    <li><a class="dropdown-item pilih-option" data-target="provinsi" data-value="DKI Jakarta" href="#">DKI Jakarta</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor Surat Usulan -->
                    <div class="mb-3">
                        <label for="nomor_surat_usulan" class="form-label">Nomor Surat Usulan</label>
                        <input type="text" class="form-control" id="nomor_surat_usulan" name="nomor_surat_usulan" placeholder="Nomor Surat Usulan" required>
                    </div>
                </div>
>>>>>>> Stashed changes
            </div>

            <!-- Tempat Kegiatan -->
            <div class="mb-3">
              <label for="tempatKegiatan" class="form-label">Tempat Kegiatan</label>
              <textarea class="form-control" id="tempatKegiatan" rows="3" placeholder="Tempat Kegiatan"></textarea>
            </div>

<<<<<<< Updated upstream
            <!-- Diusulkan Kepada -->
            <div class="form-section mb-4">
              <label for="diusulkanKepada" class="form-label">Diusulkan Kepada</label>
              <div class="d-flex align-items-end gap-2">
                <input 
                  type="text" 
                  class="form-control" 
                  id="diusulkanKepada" 
                  placeholder="Diusulkan Kepada" 
                  readonly
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
                    <li><button class="dropdown-item" type="button" onclick="setDiusulkan('Wakil Direktur I')">Wakil Direktur I</button></li>
                    <li><button class="dropdown-item" type="button" onclick="setDiusulkan('Wakil Direktur II')">Wakil Direktur II</button></li>
                    <li><button class="dropdown-item" type="button" onclick="setDiusulkan('Wakil Direktur III')">Wakil Direktur III</button></li>
                    <li><button class="dropdown-item" type="button" onclick="setDiusulkan('Wakil Direktur IV')">Wakil Direktur IV</button></li>
                  </ul>
                </div>
              </div>
            </div>

            <!-- Surat Undangan -->
            <div class="form-section mb-4">
              <label class="form-label">Surat Undangan (Jika ada)</label>
              <div class="mb-3">
                <input type="file" class="form-control" name="surat_undangan">
              </div>

              <!-- Pembiayaan -->
              <label for="pembiyaan" class="form-label">Pembiayaan</label>
              <div class="radios">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="Polban">
                  <label class="form-check-label" for="inlineRadio1">Polban</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="Penyelenggara">
                  <label class="form-check-label" for="inlineRadio2">Penyelenggara</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="polban_penyelenggara">
                  <label class="form-check-label" for="inlineRadio3">Polban dan Penyelenggara</label>
                </div>
              </div>
            </div>
          </div>

          <!-- Form Bagian Kanan -->
          <div class="col-md-6">
            <!-- Ditugaskan Sebagai -->
            <div class="mb-3 mt-4">
                <label for="ditugaskanSebagai" class="form-label">Ditugaskan Sebagai</label>
                <input type="text" class="form-control" id="ditugaskanSebagai" placeholder="Ditugaskan Sebagai">
              </div>

              <!-- Pilih Tanggal -->
              <div class="mb-3">
                <label for="tanggalPelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                <input 
                  type="text" 
                  id="tanggalPelaksanaan" 
                  class="form-control" 
                  placeholder="Tgl Berangkat → Tgl Pulang"
                  readonly
                >
              </div>

              <!-- Pagu -->
              <label for="pagu" class="form-label">Pagu</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                  Desentralisasi
                </label>
              </div>

              <!-- Alamat Kegiatan -->
              <div class="mb-3 mt-4">
                <label for="alamatKegiatan" class="form-label">Alamat Kegiatan</label>
                <textarea class="form-control" id="alamatKegiatan" rows="3" placeholder="Alamat Kegiatan"></textarea>
              </div>

              <!-- Provinsi -->
              <div class="form-section mb-4">
                <label for="diusulkanKepada" class="form-label">Provinsi</label>
                <div class="d-flex align-items-end gap-2">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="provinsi" 
                    placeholder="Provinsi" 
                    readonly
                  >
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                      Pilih
                    </button>
                    <ul class="dropdown-menu">
                      <li><div class="dropdown-item text-center py-2 text-muted">Memuat provinsi...</div></li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Nomor Surat Usulan -->
              <div class="mb-3">
                <label for="nomorSuratUsulan" class="form-label">Nomor Surat Usulan</label>
                <input type="text" class="form-control" id="nomorSuratUsulan" placeholder="Nomor Surat Usulan">
              </div>
            </div>
          </div>
          <!-- Button -->
          <div class="button-next mt-3">
            <button class="btn btn-primary" type="on-click">Selanjutnya</button>
          </div>
          </div>
=======
    <!-- Data Pegawai and Mahasiswa Section -->
    <div id="data-section" class="card p-4" style="display: none;">
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
    </div>

    <!-- Surat Tugas Section (TEMPLATE BARU DITERAPKAN DI SINI) -->
    <div id="surat-tugas-section" class="card p-0" style="display: none;">
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
>>>>>>> Stashed changes
        </div>
            </div>
          </div>
        </div> <!-- End Step 2 -->

      </form> {{-- End Form --}}
    </div>
  </div>

<<<<<<< Updated upstream
</body>
=======
@push('scripts')
<script>
    // Array untuk menyimpan personel terpilih (pegawai dan mahasiswa)
    let selectedPersonel = [];

    // Fungsi untuk mengupdate daftar personel terpilih
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

    // Fungsi untuk menampilkan daftar personel terpilih
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

    // Fungsi untuk menghapus personel dari daftar terpilih dan uncheck checkbox
    function removePersonel(personelId, type) {
        const checkbox = $(`input.personel-checkbox[data-id="${personelId}"][data-type="${type}"]`);
        if (checkbox.length) {
            checkbox.prop('checked', false);
            updateSelectedPersonel(checkbox[0]);
        }
    }

    // Handle "Select All" checkbox
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

    // Form navigation and actions
    $('#pengusulan-form').on('submit', function (e) {
        e.preventDefault();
        $('#initial-form').hide(); $('#data-section').show();
        renderSelectedPersonel();
        $('#data-pegawai-table').show(); $('#data-mahasiswa-table').hide();
        $('#data-selection-dropdown').text('Data Pegawai');
        if ($.fn.DataTable.isDataTable('#pegawaiTable')) $('#pegawaiTable').DataTable().draw();
        else $('#pegawaiTable').DataTable({ paging: true, searching: false, info: true, pageLength: 5, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
        if (!$.fn.DataTable.isDataTable('#mahasiswaTable')) $('#mahasiswaTable').DataTable({ paging: true, searching: false, info: true, pageLength: 10, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
    });
    $('#back').on('click', () => { $('#data-section').hide(); $('#initial-form').show(); });

    $('#save-draft').on('click', function () {
        // ... (logika simpan draft seperti sebelumnya, pastikan mengirim 'status_pengajuan': 'draft')
         if (selectedPersonel.length === 0) {
            Swal.fire('Peringatan!','Pilih setidaknya satu personel untuk simpan draft!','warning');
            return;
        }
        const formData = new FormData(document.getElementById('pengusulan-form'));
        selectedPersonel.forEach(p => {
            if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
            else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
        });
        formData.append('status_pengajuan', 'draft');

        fetch("{{ route('pengusul.store.pengusulan') }}", {
            method: 'POST', body: formData,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) Swal.fire('Draft Disimpan!', data.message || 'Draft berhasil disimpan.','success');
            else Swal.fire('Gagal!', data.message || 'Gagal menyimpan draft.','error');
        }).catch(error => Swal.fire('Error!', 'Terjadi kesalahan jaringan.','error'));
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
                $('#data-section').hide(); $('#surat-tugas-section').show();
                const form = $('#pengusulan-form');
                $('#nomor_surat_display').text(form.find('#nomor_surat_usulan').val() ? form.find('#nomor_surat_usulan').val() + '/PL12.C01/KP/' + new Date().getFullYear() : "___/PL12.C01/KP/" + new Date().getFullYear());
                $('#nama_kegiatan_display_text').text(form.find('#nama_kegiatan').val() || '-');
                let tempatKegiatanVal = form.find('#tempat_kegiatan').val();
                $('#penyelenggara_display').text(tempatKegiatanVal.split(',')[0] || 'Penyelenggara Kegiatan');
                $('#tanggal_pelaksanaan_display').text(form.find('#tanggal_pelaksanaan').val() || '-');
                $('#tempat_kegiatan_display').text(tempatKegiatanVal || '-');
                $('#alamat_kegiatan_display_detail').html((form.find('#alamat_kegiatan').val() || '-').replace(/\n/g, '<br>'));
                $('#ditugaskan_sebagai_display').text(form.find('#ditugaskan_sebagai').val() || '-');
                const today = new Date();
                $('#tanggal_surat_display').text(`${String(today.getDate()).padStart(2, '0')} ${["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"][today.getMonth()]} ${today.getFullYear()}`);

                const daftarPersonelContainer = $('#daftar_personel_surat_tugas');
                daftarPersonelContainer.html('');
                if (selectedPersonel.length > 0) {
                    let personelHtml = '';
                    selectedPersonel.forEach((personel) => {
                        personelHtml += `<div style="margin-bottom: 15px;">`;
                        personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">Nama</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.nama || '-'}</div></div>`;
                        if (personel.type === 'pegawai') {
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">NIP</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.nip || '-'}</div></div>`;
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">Pangkat/golongan</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.pangkat || '-'} / ${personel.golongan || '-'}</div></div>`;
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">Jabatan</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.jabatan || '-'}</div></div>`;
                        } else if (personel.type === 'mahasiswa') {
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">NIM</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.nim || '-'}</div></div>`;
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">Jurusan</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.jurusan || '-'}</div></div>`;
                            personelHtml += `<div class="surat-tugas-detail-row"><div class="surat-tugas-detail-label">Program Studi</div><div class="surat-tugas-detail-separator">:</div><div class="surat-tugas-detail-value">${personel.prodi || '-'}</div></div>`;
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

    $('#back-to-form').on('click', () => { $('#surat-tugas-section').hide(); $('#data-section').show(); });

    $('#submit-surat').on('click', function () {
        // ... (logika submit surat seperti sebelumnya, pastikan mengirim 'status_pengajuan': 'diajukan')
         Swal.fire({
            title: 'Konfirmasi Pengusulan', text: 'Data sudah benar?', icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya', cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData(document.getElementById('pengusulan-form'));
                selectedPersonel.forEach(p => {
                    if (p.type === 'pegawai') formData.append('pegawai_ids[]', p.id);
                    else if (p.type === 'mahasiswa') formData.append('mahasiswa_ids[]', p.id);
                });
                formData.append('status_pengajuan', 'diajukan');

                fetch("{{ route('pengusul.store.pengusulan') }}", {
                    method: 'POST', body: formData,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', data.message || 'Surat tugas berhasil diusulkan!','success')
                        .then(() => window.location.reload());
                    } else {
                        Swal.fire('Gagal!', data.message || 'Gagal mengusulkan.','error');
                    }
                }).catch(error => Swal.fire('Error!', 'Terjadi kesalahan jaringan.','error'));
            }
        });
    });

    // Dropdown and datepicker setup
    $(document).on('click', '#data-section .dropdown-item[data-value]', function (e) {
        e.preventDefault(); const section = $(this).data('value');
        $('#data-selection-dropdown').text($(this).text());
        $('#data-pegawai-table, #data-mahasiswa-table').hide();
        if (section === 'data-pegawai') { $('#data-pegawai-table').show(); if ($.fn.DataTable.isDataTable('#pegawaiTable')) $('#pegawaiTable').DataTable().columns.adjust().draw(); }
        else if (section === 'data-mahasiswa') { $('#data-mahasiswa-table').show(); if ($.fn.DataTable.isDataTable('#mahasiswaTable')) $('#mahasiswaTable').DataTable().columns.adjust().draw(); }
    });
    $('#search-button').on('click', function () {
        const searchTerm = $('#search-input').val().toLowerCase(); let tableAPI;
        if ($('#data-pegawai-table').is(':visible')) tableAPI = $('#pegawaiTable').DataTable();
        else if ($('#data-mahasiswa-table').is(':visible')) tableAPI = $('#mahasiswaTable').DataTable();
        else return;
        tableAPI.search(searchTerm).draw();
    });
    $('.pilih-option').on('click', function () { $('#' + $(this).data('target')).val($(this).data('value')); });
    $('input[name="pembiayaan_option"]').on('change', function(){ $('#pembiayaan_value').val($(this).val()); });
    $('#tanggal_pelaksanaan').daterangepicker({ autoUpdateInput: false, locale: { cancelLabel: 'Clear', format: 'DD/MM/YYYY' }})
        .on('apply.daterangepicker', function(ev, picker) { $(this).val(picker.startDate.format('DD/MM/YYYY') + ' → ' + picker.endDate.format('DD/MM/YYYY')); })
        .on('cancel.daterangepicker', function(ev, picker) { $(this).val(''); });

    $(document).ready(function() {
        $('#pembiayaan_value').val($('input[name="pembiayaan_option"]:checked').val());
        $('#data-pegawai-table').show(); $('#data-mahasiswa-table').hide();
        $('#data-selection-dropdown').text('Data Pegawai');
        $('#pegawaiTable').DataTable({ paging: true, searching: true, info: true, pageLength: 5, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
        $('#mahasiswaTable').DataTable({ paging: true, searching: true, info: true, pageLength: 10, lengthMenu: [5, 10, 15], order: [[1, 'asc']], columnDefs: [{ orderable: false, targets: 0 }] });
        $('#selectedPersonelContainer').hide();
    });
</script>
@endpush
>>>>>>> Stashed changes
@endsection