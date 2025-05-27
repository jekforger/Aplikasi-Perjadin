@extends('layouts.admin.pageAdmin')

@section('content')

<div class="card-container">
  <h2 class="page-title">Tambah Data Pegawai</h2> 
  <div class="card">
    <div class="label">
      <h6>*Silakan isi semua data pegawai dengan lengkap</h6>
    </div>
    
    <div class="row">
      <div class="col-md-12">
        <!-- Form Input Data Pegawai -->
        <form action="{{ route('admin.pegawai.store') }}" method="POST">
          @csrf

          <!-- Nama dan Gelar -->
          <div class="mb-3 mt-4">
            <label for="nama" class="form-label">Nama dan Gelar</label>
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Dr. Ahmad Budi, M.T." required>
          </div>

          <!-- NIP -->
          <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" placeholder="Nomor Induk Pegawai" required>
          </div>

          <!-- Pangkat -->
          <div class="mb-3">
            <label for="pangkat" class="form-label">Pangkat</label>
            <input type="text" class="form-control" id="pangkat" name="pangkat" placeholder="Pangkat" required>
          </div>

          <!-- Golongan -->
          <div class="mb-3">
            <label for="golongan" class="form-label">Golongan</label>
            <input type="text" class="form-control" id="golongan" name="golongan" placeholder="Golongan" required>
          </div>

          <!-- Jabatan -->
          <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" placeholder="Jabatan" required>
          </div>

          <!-- Tombol Simpan dan Kembali -->
          <div class="mt-3 d-flex gap-2">
            <button class="btn btn-success" type="submit">Simpan Data Pegawai</button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Kembali</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
