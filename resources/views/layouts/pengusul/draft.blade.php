@extends('layouts.pengusul.pagePengusul')

@section('content')
<div class="card-container">
    <h2 class="page-title mb-4">Draft Surat Tugas</h2>

    <div class="card shadow mb-4">
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
                        @forelse ($draftPengusulan as $index => $draft)
                            <tr>
                                <td>{{ $loop->iteration + ($draftPengusulan->currentPage() - 1) * $draftPengusulan->perPage() }}</td>
                                <td>{{ $draft->created_at ? $draft->created_at->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $draft->tanggal_berangkat ? $draft->tanggal_berangkat->format('d M Y') : 'N/A' }}</td>
                                <td>{{ $draft->nomor_surat_usulan_jurusan ?? 'N/A' }}</td>
                                <td>{{ $draft->sumber_dana ?? 'N/A' }}</td>
                               <td>
                                    <a href="{{ route('pengusul.pengusul.pengusulan', ['draft_id' => $draft->surat_tugas_id ?? 0]) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i> View/Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger ms-1" onclick="confirmDeleteDraft({{ $draft->surat_tugas_id ?? 0 }})">
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
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("pengusul.draft.delete", "") }}/' + id;
            form.style.display = 'none';

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

            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection