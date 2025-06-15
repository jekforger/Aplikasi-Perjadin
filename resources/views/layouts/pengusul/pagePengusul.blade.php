<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('include.style_pengusul')
    <title>Pengusulan</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('include.sidebar_pengusul')

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Navbar -->
            @include('include.navbar')

            <!-- Content Area -->
            <div class="container-fluid mt-4">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- PENTING: Tambahkan ini agar script dari view anak (seperti pengusulan.blade.php) bisa dieksekusi --}}
    @stack('scripts') 

    {{-- Hapus @include('include.script') dari sini karena isinya akan dibersihkan atau dipindahkan --}}
    {{-- Jika ada script yang benar-benar global, pindahkan ke layouts/main.blade.php atau biarkan di sini jika hanya untuk layout pengusul --}}
    {{-- Untuk tombol toggle sidebar, kita bisa letakkan kembali di include.script tapi pastikan itu satu-satunya fungsi --}}
</body>
</html>