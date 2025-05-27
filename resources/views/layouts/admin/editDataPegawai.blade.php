@extends('layouts.admin.pageAdmin')

@section('content')

<div class="card-container">
  <h2 class="page-title">Edit Data Pegawai</h2> 
  <div class="card">
    <div class="label">
      <h6>*Silakan ubah data pegawai jika diperlukan</h6>
    </div>
    
    <div class="row">
      <div class="col-md-12">
        <!-- Form Edit Data Pegawai -->
        <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST">
          @csrf
          @method('PUT')

          <!-- Nama dan Gelar -->
          <div class="mb-3 mt-4">
            <label for="nama" class="form-label">Nama dan Gelar</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required>
          </div>

          <!-- NIP -->
          <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $pegawai->nip) }}" required>
          </div>

          <!-- Pangkat -->
          <div class="mb-3">
            <label for="pangkat" class="form-label">Pangkat</label>
            <input type="text" class="form-control" id="pangkat" name="pangkat" value="{{ old('pangkat', $pegawai->pangkat) }}" required>
          </div>

          <!-- Golongan -->
          <div class="mb-3">
            <label for="golongan" class="form-label">Golongan</label>
            <input type="text" class="form-control" id="golongan" name="golongan" value="{{ old('golongan', $pegawai->golongan) }}" required>
          </div>

          <!-- Jabatan -->
          <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" value="{{ old('jabatan', $pegawai->jabatan) }}" required>
          </div>

          <!-- Tombol Simpan dan Kembali -->
          <div class="mt-3 d-flex gap-2">
            <button class="btn btn-success" type="submit">Perbarui Data</button>
            <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
