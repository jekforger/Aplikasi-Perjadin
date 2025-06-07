@extends('layouts.pengusul.pagePengusul')

@section('content')
<!-- Include Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- jQuery, Moment.js, dan Date Range Picker -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<div class="card-container">
  <h2 class="page-title">Pengusulan</h2> 
  <div class="card p-4">

    <form method="POST" action="{{ route('pengusul.store.pengusulan') }}" enctype="multipart/form-data">
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

          <!-- Surat Undangan -->
          <div class="form-section mb-4">
            <label for="surat_undangan" class="form-label">Surat Undangan (Jika ada)</label>
            <input type="file" class="form-control" name="surat_undangan" id="surat_undangan" accept=".pdf,.jpg,.png,.doc,.docx">
          </div>

          <!-- Pembiayaan -->
          <div class="form-section mb-4">
            <label for="pembiayaan" class="form-label">Pembiayaan</label>
            <div class="d-flex align-items-end gap-2">
              <input type="text" class="form-control" id="pembiayaan" name="pembiayaan" placeholder="Pembiayaan" readonly required>
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Pilih
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item pilih-option" data-target="pembiayaan" data-value="Polban" href="#">Polban</a></li>
                  <li><a class="dropdown-item pilih-option" data-target="pembiayaan" data-value="Penyelenggara" href="#">Penyelenggara</a></li>
                  <li><a class="dropdown-item pilih-option" data-target="pembiayaan" data-value="Polban dan Penyelenggara" href="#">Polban dan Penyelenggara</a></li>
                </ul>
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
          <div class="form-section mb-4">
            <label for="sumber_dana" class="form-label">Sumber Dana</label>
            <div class="d-flex align-items-end gap-2">
              <input type="text" class="form-control" id="sumber_dana" name="sumber_dana" placeholder="Sumber Dana" readonly required>
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Pilih
                </button>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item pilih-option" data-target="sumber_dana" data-value="RM" href="#">RM</a></li>
                  <li><a class="dropdown-item pilih-option" data-target="sumber_dana" data-value="PNBP" href="#">PNBP</a></li>
                </ul>
              </div>
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
</script>

<!-- Bootstrap Bundle (Popper + JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
