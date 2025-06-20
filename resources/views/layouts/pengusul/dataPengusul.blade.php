@extends('layouts.pengusul.layout')

@section('content')
<body>
  <div class="card-container">
    <h2 class="page-title mb-4">Pengusulan</h2>
    
    <form action="{{ route('pengusul.store.pengusulan') }}" method="POST" id="pengusulanForm">
      @csrf
      <input type="hidden" name="draft" id="draftFlag" value="0">
      
      <div class="card">
        <div class="table-responsive p-3">
          
          {{-- Header: Judul & Dropdown + Search Bar --}}
          <div class="d-flex justify-content-between align-items-center mb-3">

            {{-- Kiri: Judul dan Keterangan --}}
            <div>
              <h2 class="page-title mb-2 d-flex align-items-center">
                <span class="me-3">Data Pegawai</span>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPilihData" data-bs-toggle="dropdown" aria-expanded="false">
                    Pilih
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownPilihData">
                    <li><a class="dropdown-item" href="#" onclick="setPembiayaan('Data Pegawai'); return false;">Data Pegawai</a></li>
                    <li><a class="dropdown-item" href="#" onclick="setPembiayaan('Data Mahasiswa'); return false;">Data Mahasiswa</a></li>
                  </ul>
                </div>
              </h2>
              <div class="label">
                <h6>*Centang untuk memilih Pegawai yang ditugaskan</h6>
              </div>
            </div>

            {{-- Kanan: Search Bar --}}
            <div style="width: 40%;">
              <form method="GET" action="#">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Cari Pegawai..." value="{{ request('search') }}">
                  <button class="btn btn-primary" type="submit">Search</button>
                </div>
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
              </form>
            </div>

          </div>

          {{-- Tabel Data Pegawai --}}
          <table class="table table-bordered table-hover align-middle mb-0">
            <thead class="table-secondary">
              <tr>
                <th style="width: 5%;">
                  <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                </th>
                <th style="width: 5%;">No</th>
                <th>Nama</th>
                <th>NIP</th>
                <th>Pangkat</th>
                <th>Golongan</th>
                <th>Jabatan</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($pegawais as $pegawai)
                <tr>
                  <td>
                    <input type="checkbox" name="pegawai_ids[]" value="{{ $pegawai->id }}" 
                           class="pegawai-checkbox" 
                           data-nama="{{ $pegawai->nama }}"
                           data-nip="{{ $pegawai->nip }}"
                           data-pangkat="{{ $pegawai->pangkat }}"
                           data-golongan="{{ $pegawai->golongan }}"
                           data-jabatan="{{ $pegawai->jabatan }}"
                           onchange="updateSelectedPegawai(this)">
                  </td>
                  <td>{{ $loop->iteration + ($pegawais->currentPage() - 1) * $pegawais->perPage() }}</td>
                  <td>{{ $pegawai->nama }}</td>
                  <td>{{ $pegawai->nip }}</td>
                  <td>{{ $pegawai->pangkat }}</td>
                  <td>{{ $pegawai->golongan }}</td>
                  <td>{{ $pegawai->jabatan }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Tidak ada data pegawai.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

          {{-- Pagination dan Pengaturan Per Page --}}
          <div class="d-flex justify-content-end align-items-center mt-3 gap-3">
            <form method="GET" action="{{ route('admin.pegawai.index') }}" id="perPageForm" class="d-flex align-items-center">
              <label for="per_page" class="me-2 mb-0">Rows per page:</label>
              <select name="per_page" id="per_page" class="form-select" style="width: 80px;" onchange="document.getElementById('perPageForm').submit();">
                @foreach ([10, 25, 50, 100] as $size)
                  <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                @endforeach
              </select>
              <input type="hidden" name="search" value="{{ request('search') }}">
              <input type="hidden" name="sort" value="{{ request('sort') }}">
              <input type="hidden" name="direction" value="{{ request('direction') }}">
            </form>

            <div>
              {{ $pegawais->appends(request()->except('page'))->links() }}
            </div>
          </div>

          {{-- Daftar Pegawai Terpilih --}}
          <div class="mt-4" id="selectedPegawaiContainer" style="display: none;">
            <h5 class="mb-3">Pegawai Terpilih:</h5>
            <div class="table-responsive">
              <table class="table table-bordered" id="selectedPegawaiTable">
                <thead class="table-primary">
                  <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Pangkat</th>
                    <th>Golongan</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="selectedPegawaiList">
                  <!-- Daftar pegawai terpilih akan muncul di sini -->
                </tbody>
              </table>
            </div>
          </div>

          {{-- Tombol Aksi --}}
          <div class="d-flex justify-content-between mt-4">
            {{-- Tombol Kiri: Generate dan Simpan Draft --}}
            <div>
              <button type="button" class="btn btn-info me-1" onclick="generateSurat()">
                <i class="bi bi-file-earmark-text me-2"></i>Generate
              </button>
              <button type="button" class="btn btn-warning" onclick="simpanDraft()">
                <i class="bi bi-save me-2"></i>Simpan Draft
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</body>

@section('scripts')
<script>
  // Array untuk menyimpan pegawai terpilih
  let selectedPegawai = [];

  // Fungsi untuk select all checkbox
  function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll('.pegawai-checkbox');
    checkboxes.forEach(checkbox => {
      checkbox.checked = source.checked;
      updateSelectedPegawai(checkbox);
    });
  }

  // Fungsi untuk update daftar pegawai terpilih
  function updateSelectedPegawai(checkbox) {
    const pegawaiId = checkbox.value;
    const pegawaiData = {
      id: pegawaiId,
      nama: checkbox.dataset.nama,
      nip: checkbox.dataset.nip,
      pangkat: checkbox.dataset.pangkat,
      golongan: checkbox.dataset.golongan,
      jabatan: checkbox.dataset.jabatan
    };

    if (checkbox.checked) {
      // Tambahkan ke array jika belum ada
      if (!selectedPegawai.some(p => p.id === pegawaiId)) {
        selectedPegawai.push(pegawaiData);
      }
    } else {
      // Hapus dari array jika ada
      selectedPegawai = selectedPegawai.filter(p => p.id !== pegawaiId);
      document.getElementById('selectAll').checked = false;
    }

    renderSelectedPegawai();
  }

  // Fungsi untuk menampilkan daftar pegawai terpilih
  function renderSelectedPegawai() {
    const container = document.getElementById('selectedPegawaiContainer');
    const listElement = document.getElementById('selectedPegawaiList');
    
    listElement.innerHTML = '';
    
    if (selectedPegawai.length > 0) {
      container.style.display = 'block';
      
      selectedPegawai.forEach((pegawai, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${index + 1}</td>
          <td>${pegawai.nama}</td>
          <td>${pegawai.nip}</td>
          <td>${pegawai.pangkat}</td>
          <td>${pegawai.golongan}</td>
          <td>${pegawai.jabatan}</td>
          <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removePegawai('${pegawai.id}')">
              <i class="bi bi-trash"></i> Hapus
            </button>
          </td>
        `;
        listElement.appendChild(row);
      });
    } else {
      container.style.display = 'none';
    }
  }

  // Fungsi untuk menghapus pegawai dari daftar terpilih
  function removePegawai(pegawaiId) {
    const checkbox = document.querySelector(`.pegawai-checkbox[value="${pegawaiId}"]`);
    if (checkbox) {
      checkbox.checked = false;
      updateSelectedPegawai(checkbox);
    }
  }

  // Fungsi untuk memastikan minimal satu pegawai terpilih
  document.querySelector('form').addEventListener('submit', function(e) {
    if (selectedPegawai.length === 0) {
      e.preventDefault();
      alert('Pilih minimal satu pegawai!');
    }
  });

  // Fungsi Generate Surat
  function generateSurat() {
    if (selectedPegawai.length === 0) {
      alert('Pilih minimal satu pegawai untuk generate surat!');
      return;
    }
    
    // Lakukan proses generate surat
    alert('Sedang memproses generate surat untuk ' + selectedPegawai.length + ' pegawai terpilih');
    console.log('Pegawai terpilih untuk generate:', selectedPegawai);
    
    // Tambahkan logika generate surat disini
    // Contoh: window.location.href = '/generate-surat?pegawai_ids=' + selectedPegawai.map(p => p.id).join(',');
  }

  // Fungsi Simpan Draft
  function simpanDraft() {
    if (selectedPegawai.length === 0) {
      alert('Pilih minimal satu pegawai untuk simpan draft!');
      return;
    }
    
    // Set flag draft ke 1
    document.getElementById('draftFlag').value = '1';
    
    // Submit form
    document.getElementById('pengusulanForm').submit();
  }

  // Fungsi untuk dropdown
  function setPembiayaan(value) {
    console.log('Dipilih:', value);
  }
</script>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection