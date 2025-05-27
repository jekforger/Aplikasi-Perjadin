@extends('layouts.admin.pageAdmin')

@section('content')
<body>
  <div class="card-container">

    <h2 class="page-title mb-4">Data Pegawai</h2>

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
                <li><a class="dropdown-item" href="{{ route('admin.pegawai.create') }}">Tambah via Form</a></li>
                <li><a class="dropdown-item" href="{{ asset('template/template_pegawai.xlsx') }}" download>Download Template Excel</a></li>
              </ul>
            </div>
          </div>

          <div style="width: 40%;">
            <form method="GET" action="{{ route('admin.pegawai.index') }}">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari Pegawai..." value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Search</button>
              </div>
              {{-- Hidden inputs untuk mempertahankan sorting dan per_page saat submit search --}}
              <input type="hidden" name="sort" value="{{ request('sort') }}">
              <input type="hidden" name="direction" value="{{ request('direction') }}">
              <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            </form>
          </div>

        </div>

        <table class="table table-bordered table-hover align-middle mb-0">
          <thead class="table-secondary">
            <tr>
              <th style="width: 5%;">No</th>

              @php
                function sort_link($column, $label, $sort, $direction) {
                  $isCurrent = $sort === $column;
                  $nextDirection = ($isCurrent && $direction === 'asc') ? 'desc' : 'asc';
                  $icon = '';

                  if ($isCurrent) {
                    $icon = $direction === 'asc' ? '▲' : '▼';
                  }

                  $query = request()->all();
                  $query['sort'] = $column;
                  $query['direction'] = $nextDirection;

                  $url = route('admin.pegawai.index', $query);

                  return '<a href="'.$url.'" style="text-decoration:none;">'.$label.' '.$icon.'</a>';
                }
              @endphp

              <th>{!! sort_link('nama', 'Nama', request('sort', 'nama'), request('direction', 'asc')) !!}</th>
              <th>{!! sort_link('nip', 'NIP', request('sort', 'nama'), request('direction', 'asc')) !!}</th>
              <th>{!! sort_link('pangkat', 'Pangkat', request('sort', 'nama'), request('direction', 'asc')) !!}</th>
              <th>{!! sort_link('golongan', 'Golongan', request('sort', 'nama'), request('direction', 'asc')) !!}</th>
              <th>{!! sort_link('jabatan', 'Jabatan', request('sort', 'nama'), request('direction', 'asc')) !!}</th>

              <th style="width: 15%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pegawais as $pegawai)
              <tr>
                <td>{{ $loop->iteration + ($pegawais->currentPage() - 1) * $pegawais->perPage() }}</td>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ $pegawai->nip }}</td>
                <td>{{ $pegawai->pangkat }}</td>
                <td>{{ $pegawai->golongan }}</td>
                <td>{{ $pegawai->jabatan }}</td>
                <td>
                  <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="btn btn-sm btn-primary me-1">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <form action="{{ route('admin.pegawai.destroy', $pegawai->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                <td colspan="7" class="text-center">Tidak ada data pegawai.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="d-flex justify-content-end align-items-center mt-3 gap-3">
          <form method="GET" action="{{ route('admin.pegawai.index') }}" id="perPageForm" class="d-flex align-items-center">
            <label for="per_page" class="me-2 mb-0">Rows per page:</label>
            <select name="per_page" id="per_page" class="form-select" style="width: 80px;" onchange="document.getElementById('perPageForm').submit();">
              @foreach ([10, 25, 50, 100] as $size)
                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                  {{ $size }}
                </option>
              @endforeach
            </select>

            {{-- Keep search and sorting parameters --}}
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="sort" value="{{ request('sort') }}">
            <input type="hidden" name="direction" value="{{ request('direction') }}">
          </form>

          <div>
            {{ $pegawais->appends(request()->except('page'))->links() }}
          </div>

        </div>


      </div>
    </div>

  </div>
</body>
@endsection
