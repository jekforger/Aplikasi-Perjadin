@extends('layouts.pengusul.pagePengusul')

@section('content')
<body>
  <div class="card-container">
    <h2 class="page-title">Pengusulan</h2> 
    <div class="card">
      <div class="label">
        <h6>*Semua form wajib di isi untuk keperluan data di dalam surat, terkecuali form "Pagu"</h6>
      </div>
      <div class="row">
      <!-- Form Bagian Kiri -->
      <div class="col-md-6">
        <!-- Nama Kegiatan -->
      <div class="mb-3">
        <label for="namaKegiatan" class="form-label">Nama Kegiatan</label>
        <textarea class="form-control" id="namaKegiatan" rows="3" placeholder="Nama Kegiatan"></textarea>
      </div>

      <!-- Tempat Kegiatan -->
      <div class="mb-3">
        <label for="tempatKegiatan" class="form-label">Tempat Kegiatan</label>
        <textarea class="form-control" id="tempatKegiatan" rows="3" placeholder="Tempat Kegiatan"></textarea>
      </div>

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
      <div class="mb-3">
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
        <div class="mb-3">
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

        <!-- Nomor Surat Usulan -->
        <div class="mb-3">
          <label for="nomorSuratUsulan" class="form-label">Nomor Surat Usulan</label>
          <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Nomor Surat Usulan">
        </div>
      </div>

      
    </div>
    
    </div>
  </div>
</body>

