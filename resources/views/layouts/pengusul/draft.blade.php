@extends('layouts.pengusul.layout')

@section('title', 'Draft')
@section('pengusul_content')
<div class="pengusul-container px-4 py-3">
    <h1 class="pengusul-page-title mb-4">Draft</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">List Draft</h6>
            <form action="{{ route('pengusul.draft') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari Draft..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Pengusulan</th>
                            <th>Tanggal Berangkat</th>
                            <th>Nomor Surat Pengusulan</th>
                            <th>Sumber Dana</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($draftPengusulan as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($draftPengusulan->currentPage() - 1) * $draftPengusulan->perPage() }}</td>
                            <td>{{ $st->created_at->format('d M Y') }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d M Y') }}</td>
                            <td>{{ $st->nomor_surat_usulan_jurusan }}</td>
                            <td>{{ $st->sumber_dana }}</td>
                            <td>
                                {{-- Tombol View/Edit Draft (jika ingin bisa edit draft) --}}
                                <a href="{{ route('pengusul.pengusulan', ['draft_id' => $st->surat_tugas_id]) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View/Edit
                                </a>
                                {{-- Tombol Hapus Draft --}}
                                <button class="btn btn-sm btn-danger ms-1" onclick="confirmDeleteDraft({{ $st->surat_tugas_id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada draft yang tersimpan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $draftPengusulan->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function confirmDeleteDraft(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus draft ini?',
        text: "Draft akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Buat form dinamis untuk POST DELETE
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("pengusul.draft.delete", "") }}/' + id;
            form.style.display = 'none'; // Sembunyikan form

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form); // Tambahkan form ke body
            form.submit(); // Submit form
        }
    });
}
</script>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pengusul_content.css') }}">
@endpush