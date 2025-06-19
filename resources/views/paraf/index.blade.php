{{-- resources/views/paraf/index.blade.php --}}
@extends('layouts.main')

@section('title', 'Paraf')
{{-- Menyertakan sidebar yang sesuai --}}
@section('sidebar')
    @php
        $userRole = null;
        $roleDisplayName = 'Pengguna';
        if (\Auth::check()) {
            $userRole = \Auth::user()->role;
            $loginController = new \App\Http\Controllers\Auth\LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }
    @endphp
    @if ($userRole)
        @if (in_array($userRole, ['wadir_1', 'wadir_2', 'wadir_3', 'wadir_4']))
            @include('layouts.Wadir.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'direktur')
            @include('direktur.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pelaksana')
            @include('layouts.pelaksana.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pengusul')
            @include('layouts.pengusul.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'admin')
            @include('layouts.admin.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'bku')
            @include('layouts.bku.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @else
            <div></div>
        @endif
    @else
        <div></div>
    @endif
@endsection

@section('content')
<div class="paraf-container px-4 py-3">
    <h1 class="paraf-page-title mb-4">Paraf</h1>

    <div class="p-4 shadow-sm bg-white rounded">
        <h5 class="mb-3">Detail</h5>
    </div>
</div>

{{-- Modal Upload File (DITAMBAHKAN DI SINI) --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload Dokumen Untuk Paraf</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('paraf.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_file" class="form-label">Pilih Dokumen (PDF, DOC, DOCX, maks 5MB)</label>
                        <input class="form-control" type="file" id="document_file" name="document_file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/paraf.css') }}">
@endpush

@push('scripts')
    {{-- JS untuk halaman paraf (jika ada) --}}
@endpush