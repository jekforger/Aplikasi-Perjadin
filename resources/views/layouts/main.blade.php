{{-- resources/views/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi SPPD Polban') }}</title>

    <link rel="icon" href="{{ asset('img/polban2.png') }}" type="image/png"> {{-- Favicon --}}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Font Awesome (for icons used in dashboards like Wadir) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCXdJf3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Custom Global CSS (Jika ada, seperti main.css) --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}

    @stack('styles') {{-- Untuk CSS spesifik halaman anak --}}
</head>
<body>
    {{-- Global Success/Error Alert for App Layout (after login) --}}
    @if (session('success_message'))
        <div class="alert alert-success alert-dismissible fade show global-alert-app-top" role="alert">
            {{ session('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show global-alert-app-top" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Wrapper utama untuk sidebar dan konten, dan toggle button --}}
    <div class="d-flex" id="wrapper">
        {{-- Sidebar (akan di-yield oleh layout anak, misal layouts.Wadir.layout) --}}
        @yield('sidebar')

        {{-- Page Content Wrapper (konten utama yang bergeser) --}}
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Navbar -->
            @include('include.navbar')

            <!-- Content Area (dengan padding) -->
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>


    <!-- PENTING: URUTAN LOADING SCRIPT -->

    <!-- 1. jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 2. Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Global JavaScript for Sidebar Toggle -->
    {{-- Ini harus ada di sini jika sidebar toggle adalah fitur global aplikasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-btn');
            const sidebar = document.getElementById('sidebar');
            const pageContentWrapper = document.getElementById('page-content-wrapper');

            if (toggleBtn && sidebar && pageContentWrapper) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('active');
                    }
                });

                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('active');
                    }
                });

                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768 &&
                        sidebar.classList.contains('active') &&
                        !sidebar.contains(event.target) &&
                        !toggleBtn.contains(event.target)) {
                        sidebar.classList.remove('active');
                    }
                });
            } else {
                console.warn('Sidebar toggle elements or page content wrapper not found. Layout interactivity might be affected.');
            }
        });
    </script>

    @stack('scripts') {{-- Untuk JS spesifik halaman anak --}}
</body>
</html>