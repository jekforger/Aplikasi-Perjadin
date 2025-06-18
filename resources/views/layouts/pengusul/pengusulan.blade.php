@extends('layouts.pengusul.layout')

@section('content')
<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="card-container">
    <h2 class="page-title">Pengusulan</h2>

    <!-- Initial Form Section -->
    <div id="initial-form" class="card p-4" style="display: block;">
        <form method="POST" action="{{ route('pengusul.store.pengusulan') }}" enctype="multipart/form-data" id="pengusulan-form">
            @csrf

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

                    <!-- Sumber Dana -->
                    <div class="mb-3">
                        <label for="pagu" class="form-label">Pagu</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
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
            </div>

            <!-- Button Submit -->
            <div class="button-next mt-3">
                <button type="submit" class="btn btn-primary">Selanjutnya</button>
            </div>
        </form>
    </div>

    <!-- Data Pegawai and Mahasiswa Section -->
    <div id="data-section" class="card p-4" style="display: none;">
        <h3>Data Pegawai 2025</h3>
        <p>*Centang untuk memilih pegawai yang akan ditugaskan!</p>
         
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
        <div class="table-responsive" id="data-pegawai-table">
            <table class="table table-striped" id="pegawaiTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all-pegawai"></th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Pangkat</th>
                        <th>Golongan</th>
                        <th>Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="pegawai[]" value="Dini Rahmahati"></td>
                        <td>Dini Rahmahati, S.ST., M.Sc.</td>
                        <td>19910196201903206</td>
                        <td>Penata Muda</td>
                        <td>III/b</td>
                        <td>Koordinator Bidang Kehumasan</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="pegawai[]" value="Adhitya Listyani"></td>
                        <td>Adhitya Listyani, A.Md.</td>
                        <td>198104282009122001</td>
                        <td>Penata Muda</td>
                        <td>III/b</td>
                        <td>Staff Humas</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="pegawai[]" value="Rhona Davidson"></td>
                        <td>Rhona Davidson</td>
                        <td>Integration Specialist</td>
                        <td>Tokyo</td>
                        <td>55</td>
                        <td>2010/10/14</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="pegawai[]" value="Quinn Flynn"></td>
                        <td>Quinn Flynn</td>
                        <td>Support Lead</td>
                        <td>Edinburgh</td>
                        <td>22</td>
                        <td>2013/03/03</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="pegawai[]" value="Jena Gaines"></td>
                        <td>Jena Gaines</td>
                        <td>Office Manager</td>
                        <td>London</td>
                        <td>30</td>
                        <td>2008/12/19</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Pegawai</h3>
        <div class="table-responsive">
            <table class="table table-striped" id="selectedPegawaiTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dini Rahmahati, S.ST., M.Sc.</td>
                        <td>19910196201903206</td>
                        <td>Koordinator Bidang Kehumasan</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                    <tr>
                        <td>Adhitya Listyani, A.Md.</td>
                        <td>198104282009122001</td>
                        <td>Staff Humas</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                    <tr>
                        <td>John, A.Md. Kom.</td>
                        <td>19910196201903206</td>
                        <td>Staff Humas</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Mahasiswa</h3>
        <div class="table-responsive" id="data-mahasiswa-table" style="display: none;">
            <table class="table table-striped" id="mahasiswaTable">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all-mahasiswa"></th>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="mahasiswa[]" value="Fahrizal Mudzqi Maulana"></td>
                        <td>Fahrizal Mudzqi Maulana</td>
                        <td>221511049</td>
                        <td>Teknik Komputer dan Informatika</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="mahasiswa[]" value="Fares Rama Mahdika"></td>
                        <td>Fares Rama Mahdika</td>
                        <td>221511050</td>
                        <td>Teknik Informatika</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" name="mahasiswa[]" value="Naufal Syafiq Somantri"></td>
                        <td>Naufal Syafiq Somantri</td>
                        <td>221511024</td>
                        <td>Teknik Komputer dan Informatika</td>
                        <td><button class="btn btn-danger btn-sm delete-row">Hapus</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="button-next mt-3">
            <button type="button" class="btn btn-primary" id="back">Kembali</button>
            <button type="button" class="btn btn-success" id="create-task">Buat Surat Tugas</button>
            <button type="button" class="btn btn-warning" id="save-draft">Simpan Draft</button>
        </div>
    </div>

    <!-- Surat Tugas Section -->
    <div id="surat-tugas-section" class="card p-4" style="display: none;">
        <div class="text-center mb-4">
            <img src="{{ asset('images/polban-logo.png') }}" alt="Polban Logo" width="100">
            <h5 class="mt-2 mb-1">KEMENTERIAN PENDIDIKAN TINGGI, SAINS,</h5>
            <h5 class="mb-1">DAN TEKNOLOGI</h5>
            <h4>POLITEKNIK NEGERI BANDUNG</h4>
            <p class="mb-1">Jalan Gegerkalong Hilir, Desa Ciwaruga, Bandung 40012, Kotak Pos 1234,</p>
            <p class="mb-1">Telepon: (022) 2013789, Faksimile: (022) 2013889</p>
            <p>Laman: www.polban.ac.id, Pos Elektronik: polban@polban.ac.id</p>
            <hr class="my-2">
        </div>
        
        <h4 class="text-center mb-3">SURAT TUGAS</h4>
        <h5 class="text-center mb-4">Nomor: ___/PL12.C01/KP/2025</h5>

        <p class="mb-4">Direktur Politeknik Negeri Bandung menugaskan kepada yang nama-namanya tercantum di dalam lampiran pada surat tugas ini:</p>

        <table class="table table-borderless mb-4">
            <tr>
                <td style="width: 30%"><strong>Ditugaskan Sebagai</strong></td>
                <td>: {{ $ditugaskan_sebagai ?? 'Peserta kompetisi' }}</td>
            </tr>
            <tr>
                <td><strong>Nama Kegiatan</strong></td>
                <td>: {{ $nama_kegiatan ?? 'Choral Orchestra Folklore Festival 2024' }}</td>
            </tr>
            <tr>
                <td><strong>Tempat Kegiatan</strong></td>
                <td>: {{ $tempat_kegiatan ?? 'SMK Yogyakarta' }}</td>
            </tr>
            <tr>
                <td><strong>Alamat Kegiatan</strong></td>
                <td>: {{ $alamat_kegiatan ?? 'Jl. PG. Madukismo, Jonseatan, Næstihario, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pelaksanaan</strong></td>
                <td>: {{ $tanggal_pelaksanaan ?? '4 s.d 8 November 2024' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Berlaku</strong></td>
                <td>: {{ $tanggal_pelaksanaan ?? '4 s.d 8 November 2024' }}</td>
            </tr>
        </table>

        <p class="mb-4">Surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>

        <div class="d-flex justify-content-between mt-5">
            <div>
                <p class="mb-1"><strong>Tembusan:</strong></p>
                <ol class="mb-0">
                    <li>Para Wakil Direktur</li>
                    <li>Ketua Jurusan</li>
                </ol>
            </div>
            <div class="text-right">
                <p>Bandung, {{ date('d F Y') }}</p>
                <p>Direktur,</p>
                <div style="margin-top: 80px">
                    <p class="mb-0"><strong>Maryani, S.E., M.Si., Ph.D.</strong></p>
                    <p class="mb-0">NIP 196405041990032001</p>
                </div>
            </div>
        </div>

        <div class="button-next mt-3">
            <button type="button" class="btn btn-primary" id="back-to-form">Kembali</button>
            <button type="button" class="btn btn-primary" id="submit-surat">Usulkan</button>
        </div>
    </div>
</div>

<!-- Script Dropdown dan Datepicker -->
<script>
    // Untuk set nilai input dari dropdown
    document.querySelectorAll('.pilih-option').forEach(item => {
        item.addEventListener('click', function () {
            const inputTarget = document.getElementById(this.dataset.target);
            if (inputTarget) {
                inputTarget.value = this.dataset.value;
            }
        });
    });

    // Update pembiayaan input based on radio selection
    $('input[name="pembiayaan_option"]').on('change', function () {
        $('#pembiayaan').val($(this).val());
    });

    // Date range picker
    $(function () {
        $('#tanggal_pelaksanaan').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
            }
        });

        $('#tanggal_pelaksanaan').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' → ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#tanggal_pelaksanaan').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    });

    // Handle form submission to show data section
    $('#pengusulan-form').on('submit', function (e) {
        e.preventDefault();
        $('#initial-form').hide();
        $('#data-section').show();
        // Ensure Data Pegawai table is visible by default
        $('#data-pegawai-table').show();
        $('#data-mahasiswa-table').hide();
        // Reinitialize DataTables
        if ($.fn.DataTable.isDataTable('#pegawaiTable')) {
            $('#pegawaiTable').DataTable().destroy();
        }
        $('#pegawaiTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 5,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
        if ($.fn.DataTable.isDataTable('#selectedPegawaiTable')) {
            $('#selectedPegawaiTable').DataTable().destroy();
        }
        $('#selectedPegawaiTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 10,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']]
        });
        if ($.fn.DataTable.isDataTable('#mahasiswaTable')) {
            $('#mahasiswaTable').DataTable().destroy();
        }
        $('#mahasiswaTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 10,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });

    // Handle back button
    $('#back').on('click', function () {
        $('#data-section').hide();
        $('#initial-form').show();
    });

    // Handle save draft button
    $('#save-draft').on('click', function () {
        Swal.fire({
            title: 'Draft Disimpan!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        // Add your save logic here
    });

    // Handle create task button with confirmation and generate surat
    $('#create-task').on('click', function () {
        // Get selected checkboxes for pegawai and mahasiswa
        const selectedPegawai = $('input[name="pegawai[]"]:checked').map(function() {
            return $(this).val();
        }).get();
        const selectedMahasiswa = $('input[name="mahasiswa[]"]:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedPegawai.length === 0 && selectedMahasiswa.length === 0) {
            Swal.fire({
                title: 'Peringatan!',
                text: 'Pilih setidaknya satu pegawai atau mahasiswa sebelum membuat surat tugas!',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: 'Yakin untuk membuat ke dalam surat tugas?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Hide data section and show surat tugas section
                $('#data-section').hide();
                $('#surat-tugas-section').show();

                // Populate surat tugas fields with form data
                $('#surat-tugas-section #nama_kegiatan').text($('#nama_kegiatan').val() || 'Festival Kesenian Nasional');
                $('#surat-tugas-section #tempat_kegiatan').text($('#tempat_kegiatan').val() || 'SMK Yogyakarta, Jl. PG. Madukismo, Jl. Gembiraloka, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta');
                $('#surat-tugas-section #alamat_kegiatan').text($('#alamat_kegiatan').val() || 'Jl. PG. Madukismo, Jl. Gembiraloka, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta');
                $('#surat-tugas-section #tanggal_pelaksanaan').text($('#tanggal_pelaksanaan').val() || '4 s.d 8 November 2024');
                $('#surat-tugas-section #ditugaskan_sebagai').text($('#ditugaskan_sebagai').val() || 'Koordinator dan Anggota');
            }
        });
    });

    // Handle back to form button
    $('#back-to-form').on('click', function () {
        $('#surat-tugas-section').hide();
        $('#data-section').show();
    });

    // Handle submit surat button with custom alert and redirect
    $('#submit-surat').on('click', function () {
        Swal.fire({
            title: 'Konfirmasi Pengusulan',
            text: 'Data yang dimasukkan sudah benar?\nYa\tTidak',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Surat tugas berhasil diusulkan!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to initial form
                    $('#surat-tugas-section').hide();
                    $('#initial-form').show();
                });
            }
        });
    });

    // Handle data selection dropdown change
    $(document).on('click', '.dropdown-item[data-value]', function (e) {
        e.preventDefault();
        const section = $(this).data('value');
        $('#data-selection-dropdown').text($(this).text());
        
        // Hide all tables first
        $('#data-pegawai-table').hide();
        $('#data-mahasiswa-table').hide();

        // Show the selected table and hide the opposite one
        if (section === 'data-pegawai') {
            $('#data-pegawai-table').show();
            $('#data-mahasiswa-table').hide();
            if ($.fn.DataTable.isDataTable('#pegawaiTable')) {
                $('#pegawaiTable').DataTable().destroy();
            }
            $('#pegawaiTable').DataTable({
                paging: true,
                searching: false,
                info: true,
                pageLength: 5,
                lengthMenu: [5, 10, 15],
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: 0 }]
            });
        } else if (section === 'data-mahasiswa') {
            $('#data-mahasiswa-table').show();
            $('#data-pegawai-table').hide();
            if ($.fn.DataTable.isDataTable('#mahasiswaTable')) {
                $('#mahasiswaTable').DataTable().destroy();
            }
            $('#mahasiswaTable').DataTable({
                paging: true,
                searching: false,
                info: true,
                pageLength: 10,
                lengthMenu: [5, 10, 15],
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: 0 }]
            });
        }
    });

    // Handle search button
    $('#search-button').on('click', function () {
        const searchTerm = $('#search-input').val().toLowerCase();
        $('#pegawaiTable tbody tr').each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchTerm));
        });
        $('#mahasiswaTable tbody tr').each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(searchTerm));
        });
    });

    // Handle select all checkbox for pegawai
    $('#select-all-pegawai').on('click', function () {
        $('input[name="pegawai[]"]').prop('checked', this.checked);
    });

    // Handle select all checkbox for mahasiswa
    $('#select-all-mahasiswa').on('click', function () {
        $('input[name="mahasiswa[]"]').prop('checked', this.checked);
    });

    // Handle delete row button
    $('.delete-row').on('click', function () {
        $(this).closest('tr').remove();
    });

    // Initialize DataTables on page load
    $(document).ready(function () {
        $('#data-pegawai-table').show();
        $('#data-mahasiswa-table').hide();
        $('#pegawaiTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 5,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
        $('#selectedPegawaiTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 10,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']]
        });
        $('#mahasiswaTable').DataTable({
            paging: true,
            searching: false,
            info: true,
            pageLength: 10,
            lengthMenu: [5, 10, 15],
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });
</script>
@endsection