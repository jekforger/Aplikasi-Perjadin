{{-- resources/views/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aplikasi SPPD Polban') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('images/polban_logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCXdJf3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    
    @stack('styles') {{-- Untuk CSS spesifik halaman (misalnya wadir.css) --}}
</head>
<body> {{-- Hapus d-flex flex-column min-vh-100 dari body, akan diatur di main.css --}}

    {{-- Global Success Alert untuk semua halaman aplikasi (setelah login) --}}
    @if (session('success_message'))
        <div class="alert alert-success alert-dismissible fade show global-alert-app-top" role="alert">
            {{ session('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('sidebar') {{-- Sidebar akan di-yield di sini (akan menjadi fixed) --}}

    <div class="main-wrapper"> {{-- Pembungkus utama untuk navbar dan konten, akan ada margin-left --}}
        @include('partials.navbar') {{-- Navbar Aplikasi Anda (akan diposisikan di atas content) --}}

        <main class="main-content-area"> {{-- Area konten utama --}}
            @yield('content') {{-- Konten halaman spesifik (dashboard, pengusulan, dll.) --}}
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts') {{-- Untuk JS spesifik halaman --}}
</body>
</html>