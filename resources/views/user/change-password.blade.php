{{-- resources/views/user/change-password.blade.php --}}
@extends('layouts.main') {{-- Mengextend layout utama aplikasi --}}

{{-- Sertakan sidebar di sini --}}
@section('sidebar')
    {{-- Ambil userRole dan roleDisplayName langsung di sini --}}
    @php
        $userRole = null;
        $roleDisplayName = 'Pengguna'; // Default
        if (\Auth::check()) { // Pastikan user sudah login
            $userRole = \Auth::user()->role;
            $loginController = new \App\Http\Controllers\Auth\LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }
    @endphp

    {{-- Include sidebar partial berdasarkan role yang terdeteksi --}}
    @if ($userRole) {{-- Pastikan ada user role --}}
        @if (in_array($userRole, ['wadir_1', 'wadir_2', 'wadir_3', 'wadir_4']))
            @include('layouts.Wadir.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pengusul')
            @include('layouts.pengusul.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'pelaksana')
            @include('layouts.pelaksana.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'direktur')
            @include('layouts.direktur.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'admin')
            @include('layouts.admin.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @elseif ($userRole == 'bku')
            @include('layouts.bku.partials.sidebar', ['userRole' => $userRole, 'roleDisplayName' => $roleDisplayName])
        @else
            {{-- Fallback jika role tidak memiliki sidebar spesifik --}}
            <div>{{-- Sidebar content for this role is not defined --}}</div>
        @endif
    @else
        {{-- Jika tidak ada user yang login atau role tidak terdeteksi --}}
        <div></div>
    @endif
@endsection

@section('content')
<div class="container-fluid px-0 change-password-container">
    <h1 class="page-title change-password-title mb-4">Change Password</h1>

    <div class="card shadow mb-4 change-password-card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Terjadi kesalahan validasi. Mohon periksa kembali input Anda.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('user.change-password') }}">
                @csrf

                {{-- Password Lama --}}
                <div class="mb-3 form-group-password">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <div class="input-group-password">
                        <input type="password" class="form-control custom-form-input password-input-with-icon @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required placeholder="Password Lama">
                        <span class="password-toggle-icon" id="toggleCurrentPassword">
                            <i class="bi bi-eye-fill" id="toggleCurrentPasswordIcon"></i>
                        </span>
                        @error('current_password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Password Baru --}}
                <div class="mb-3 form-group-password">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <div class="input-group-password">
                        <input type="password" class="form-control custom-form-input password-input-with-icon @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required placeholder="Password Baru">
                        <span class="password-toggle-icon" id="toggleNewPassword">
                            <i class="bi bi-eye-fill" id="toggleNewPasswordIcon"></i>
                        </span>
                        @error('new_password')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="mb-4 form-group-password">
                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group-password">
                        <input type="password" class="form-control custom-form-input password-input-with-icon @error('new_password_confirmation') is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Konfirmasi Password Baru">
                        <span class="password-toggle-icon" id="toggleNewPasswordConfirmation">
                            <i class="bi bi-eye-fill" id="toggleNewPasswordConfirmationIcon"></i>
                        </span>
                        @error('new_password_confirmation')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary custom-blue-button">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/user.js') }}"></script>
@endpush