@extends('layouts.pengusul.layout')

@section('pengusul_content')
<div class="card-container">
    <h2 class="page-title mb-4">Status Pengusulan</h2>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Status Pengusulan Saya</h6>
            <form action="{{ route('pengusul.status') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari..." value="{{ request('search') }}">
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
                            <th>Nomor Surat Pengantar</th>
                            <th>Sumber Dana</th>
                            <th>Status</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($statusPengusulan as $index => $st)
                        <tr>
                            <td>{{ $loop->iteration + ($statusPengusulan->currentPage() - 1) * $statusPengusulan->perPage() }}</td>
                            <td>{{ $st->created_at->format('d M Y') }}</td>
                            <td>{{ $st->tanggal_berangkat->format('d M Y') }}</td>
                            <td>{{ $st->nomor_surat_usulan_jurusan }}</td>
                            <td>{{ $st->sumber_dana }}</td>
                            <td>
                                @php
                                    $badgeClass = '';
                                    $showReasonButton = false;
                                    switch ($st->status_surat) {
                                        case 'draft': $badgeClass = 'bg-secondary'; break;
                                        case 'pending_wadir_review': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'approved_by_wadir': $badgeClass = 'bg-info'; break;
                                        case 'rejected_by_wadir': $badgeClass = 'bg-danger'; $showReasonButton = true; break;
                                        case 'reverted_by_wadir': $badgeClass = 'bg-info text-dark'; $showReasonButton = true; break;
                                        case 'approved_by_direktur': $badgeClass = 'bg-success'; break;
                                        case 'rejected_by_direktur': $badgeClass = 'bg-danger'; $showReasonButton = true; break;
                                        case 'reverted_by_direktur': $badgeClass = 'bg-info text-dark'; $showReasonButton = true; break;
                                        case 'diterbitkan': $badgeClass = 'bg-primary'; break;
                                        case 'laporan_selesai': $badgeClass = 'bg-success'; break; // Contoh status final
                                        default: $badgeClass = 'bg-light text-dark'; break;
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($st->status_surat)) }}</span>
                                @if ($showReasonButton && $st->catatan_revisi)
                                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="showReason('{{ $st->catatan_revisi }}')">Lihat Alasan</button>
                                @endif
                            </td>
                            <td>
                                {{-- Link ke halaman detail/view surat tugas (jika ada) --}}
                                <a href="#" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                {{-- Tombol Edit untuk draft atau yang dikembalikan (opsional) --}}
                                @if ($st->status_surat == 'draft' || Str::contains($st->status_surat, 'reverted'))
                                    <a href="#" class="btn btn-sm btn-outline-warning ms-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengusulan dengan status ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $statusPengusulan->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function showReason(reason) {
    Swal.fire({
        title: 'Alasan Penolakan/Revisi',
        html: `<p>${reason}</p>`,
        icon: 'info',
        confirmButtonText: 'OK'
    });
}
</script>
@endsection