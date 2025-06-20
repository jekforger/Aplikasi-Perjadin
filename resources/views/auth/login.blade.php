@extends('layouts.authMain') {{-- UBAH INI: Pastikan extend 'layouts.authMain' yang sudah kita buat --}}

@section('content')
<div class="login-full-page-wrapper">
    {{-- Global alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show global-alert-top" role="alert">
            Email dan Password yang anda masukkan salah atau tidak sesuai. Mohon periksa kembali.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Session success/status message (from previous redirect) --}}
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show global-alert-top" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Session error message (from LoginController validation exception) --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show global-alert-top" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-0 h-100 justify-content-center align-items-center">
        {{-- Left Panel (Login Form) --}}
        <div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-center left-panel">
            <div class="login-form-wrapper login-form-padding">
                <div class="text-center app-header-section">
                    <img src="{{ asset('img/polban.png') }}" alt="Polban Logo" class="img-fluid polban-logo-select-role">
                    <h4 class="fw-bold app-title-main">Aplikasi Perjalanan Dinas Politeknik Negeri Bandung</h4>
                </div>

                {{-- UBAH INI: Menggunakan $displayName dari controller --}}
                <h3 class="role-select-title-login">Login Sebagai {{ $displayName ?? 'Role' }}</h3>


                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf

                    {{-- Hidden input untuk mengirim role kembali ke controller --}}
                    <input type="hidden" name="role" value="{{ $role }}"> {{-- Menggunakan $role yang sudah dikirim --}}

                    <div class="mb-3 form-group-custom">
                        <label for="email" class="form-label">Email Polban</label> {{-- Hapus visually-hidden jika ingin label terlihat --}}
                        <input type="email" class="form-control custom-form-input @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email Polban">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4 form-group-custom">
                        <label for="password" class="form-label">Password</label> {{-- Hapus visually-hidden jika ingin label terlihat --}}
                        <div class="position-relative">
                            <input type="password" class="form-control custom-form-input password-input-with-icon @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Password">
                            <span class="password-toggle-icon" id="togglePassword">
                                <i class="bi bi-eye-fill" id="togglePasswordIcon"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-4">
                        <button type="submit" class="btn btn-primary btn-lg custom-btn-orange">Login</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Right Panel (Illustration) - Hidden on mobile --}}
        <div class="col-lg-8 d-none d-lg-flex align-items-center justify-content-center right-panel"> {{-- Mengubah col-lg-7 menjadi col-lg-8 untuk konsistensi dengan ilustrasi --}}
            <img src="{{ asset('img/login.png') }}" alt="Login Illustration" class="img-fluid illustration-img">
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endpush