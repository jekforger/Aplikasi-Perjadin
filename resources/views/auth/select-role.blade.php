@extends('layouts.authMain') {{-- Pastikan ini meng-extend layout yang benar --}}

@section('content')
<div class="login-full-page-wrapper">
    <div class="row g-0 h-100">
        {{-- Left Panel (Login Form) --}}
        <div class="col-lg-4 col-md-12 d-flex align-items-center justify-content-center left-panel">
            <div class="login-form-wrapper login-form-padding">
                <div class="text-center app-header-section"> 
                    <img src="{{ asset('img/polban.png') }}" alt="Polban Logo" class="img-fluid polban-logo-select-role">
                    <h4 class="fw-bold app-title-main">Aplikasi Perjalanan Dinas Politeknik Negeri Bandung</h4>
                </div>

                <h3 class="role-select-title">Pilih Role</h3>

                {{-- Global alerts akan di-handle oleh layouts.authMain.blade.php --}}
                {{-- Anda bisa menghapus @if (session('error')) di sini jika sudah ada di authMain --}}
                {{-- @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif --}}

                <form method="GET" action="{{ route('login.form') }}">
                    <div class="mb-4">
                        <label for="role_select" class="form-label visually-hidden">Pilih Role</label>
                        <div class="select-wrapper">
                            <select class="form-select form-select-lg custom-select-role @error('role') is-invalid @enderror" id="role_select" name="role" required>
                                <option value="" disabled selected>Pilih Role</option>
                                <option value="pengusul">Pengusul</option>
                                <option value="pelaksana">Pelaksana</option>
                                <option value="bku">Badan Keuangan Umum (BKU)</option>
                                {{-- UBAH BAGIAN INI --}}
                                <option value="wadir_1">Wakil Direktur I</option>
                                <option value="wadir_2">Wakil Direktur II</option>
                                <option value="wadir_3">Wakil Direktur III</option>
                                <option value="wadir_4">Wakil Direktur IV</option>
                                {{-- AKHIR UBAH BAGIAN INI --}}
                                <option value="direktur">Direktur</option>
                                <option value="sekdir">Sekretaris Direktur</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg custom-btn-orange">Login</button>
                    </div>
                </form>

                <!-- <p class="text-center mt-5 text-register">Belum punya akun?
                    <span class="text-muted small-text-register text-orange-daftar">Daftar</span>
                </p> -->
            </div>
        </div>

        {{-- Right Panel (Illustration) - Hidden on mobile --}}
        <div class="col-lg-8 d-none d-lg-flex align-items-center justify-content-center right-panel"> 
            <img src="{{ asset('img/login.png') }}" alt="Login Illustration" class="img-fluid illustration-img">
        </div>
    </div>
</div>
@endsection