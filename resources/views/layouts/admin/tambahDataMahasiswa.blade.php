@extends('layouts.admin.pageAdmin')

@section('content')
  <div class="card-container">
    <h2 class="page-title">Tambah Data Mahasiswa</h2> 
    <div class="card">
      <div class="label">
        <h6>*Silakan isi semua data mahasiswa dengan lengkap</h6>
      </div>
      
      <div class="row">
        <div class="col-md-12">
          <!-- Form Input Data Mahasiswa -->
          <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
            @csrf

            <!-- Nama -->
            <div class="mb-3 mt-4">
              <label for="nama" class="form-label">Nama Mahasiswa</label>
              <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Budi Santoso" required>
            </div>

            <!-- NIM -->
            <div class="mb-3">
              <label for="nim" class="form-label">NIM</label>
              <input type="text" class="form-control" id="nim" name="nim" placeholder="Nomor Induk Mahasiswa" required>
            </div>

            <!-- Jurusan -->
            <div class="mb-3">
              <label for="jurusan" class="form-label">Jurusan</label>
              <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Contoh: FTI" required>
            </div>

            <!-- Program Studi -->
            <div class="mb-3">
              <label for="prodi" class="form-label">Program Studi</label>
              <input type="text" class="form-control" id="prodi" name="prodi" placeholder="Contoh: Teknik Informatika" required>
            </div>

            <!-- Tombol Simpan -->
            <div class="button-next mt-3">
              <button class="btn btn-primary" type="submit">Simpan Data Mahasiswa</button>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
@endsection
