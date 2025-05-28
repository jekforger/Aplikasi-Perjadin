@extends('layouts.admin.pageAdmin')

@section('content')
<body>
  <div class="card-container">

    <h2 class="page-title mb-4">Data Mahasiswa</h2>

    {{-- Baris search dan tombol tambah data --}}
    <div class="card">
      <div class="table-responsive p-3">

        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <div class="dropdown">
              <button class="btn btn-primary dropdown-toggle" type="button" id="tambahDataDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Tambah Data
              </button>
              <ul class="dropdown-menu" aria-labelledby="tambahDataDropdown">
                <li><a class="dropdown-item" href="{{ route('admin.mahasiswa.create') }}">Tambah via Form</a></li>
                <li><a class="dropdown-item" href="{{ asset('template/template_mahasiswa.xlsx') }}" download>Download Template Excel</a></li>
              </ul>
            </div>
          </div>

          <div style="width: 40%;">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Mahasiswa..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Search</button>
              </div>
            </form>
          </div>
        </div>

        <table class="table table-bordered table-hover align-middle mb-0">
          <thead class="table-secondary">
            <tr>
              <th style="width: 5%;">No</th>
              <th>Nama</th>
              <th>NIM</th>
              <th>Jurusan</th>
              <th>Prodi</th>
              <th style="width: 15%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($mahasiswa as $index => $mhs)
              <tr>
                <td>{{ $loop->iteration + ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() }}</td>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->jurusan }}</td>
                <td>{{ $mhs->prodi }}</td>
                <td>
                  <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}" class="btn btn-sm btn-primary me-1">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('admin.mahasiswa.destroy', $mhs->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Tidak ada data mahasiswa.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="d-flex justify-content-end align-items-center mt-3">
          <div class="me-3">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}">
              <select name="perPage" onchange="this.form.submit()" class="form-select form-select-sm">
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
              </select>
            </form>
          </div>
          <div>
            {{ $mahasiswa->appends(request()->query())->links() }}
          </div>
        </div>

      </div>
    </div>

  </div>
</body>
@endsection
