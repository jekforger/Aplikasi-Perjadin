@extends('layouts.admin.pageAdmin')

@section('content')
<div class="container mt-4">
  <div class="card">
    <div class="card-header">
      <h4>Edit Data Mahasiswa</h4>
    </div>
    <div class="card-body">
      <form action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label for="nama" class="form-label">Nama</label>
          <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $mahasiswa->nama) }}" required>
        </div>

        <div class="mb-3">
          <label for="nim" class="form-label">NIM</label>
          <input type="text" name="nim" id="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" required>
        </div>

        <div class="mb-3">
          <label for="jurusan" class="form-label">Jurusan</label>
          <input type="text" name="jurusan" id="jurusan" class="form-control" value="{{ old('jurusan', $mahasiswa->jurusan) }}">
        </div>

        <div class="mb-3">
          <label for="prodi" class="form-label">Program Studi</label>
          <input type="text" name="prodi" id="prodi" class="form-control" value="{{ old('prodi', $mahasiswa->prodi) }}">
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
