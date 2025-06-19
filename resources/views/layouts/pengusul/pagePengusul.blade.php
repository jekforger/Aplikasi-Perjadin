<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Penting untuk AJAX forms --}}
    <title>Pengusulan SPPD Polban</title> {{-- Judul halaman --}}
    <link rel="icon" type="image/png" href="{{ asset('img/polban2.png') }}"> {{-- Favicon --}}

    <!-- Google Fonts: Poppins (sudah ada di proyek Anda) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS (sudah ada di proyek Anda) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons (sudah ada di proyek Anda) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font Awesome (sudah ada di proyek Anda, untuk ikon di dashboard Wadir dll.) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCXdJf3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- DataTables CSS (GLOBAL - diperlukan untuk tabel pegawai/mahasiswa) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Date Range Picker CSS (GLOBAL - diperlukan untuk input tanggal) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <!-- SweetAlert2 CSS (Opsional, jika Anda ingin CSS-nya dimuat global untuk tampilan alert) -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> --}}


    <!-- Custom CSS for Pengusul Layout (sidebar, navbar positioning, card-container, dll.) -->
    <link rel="stylesheet" href="{{ asset('css/pengusul.css') }}">
    {{-- Jika ada css/pengusulan.css yang memiliki style unik non-layout, tambahkan di sini --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/pengusulan.css') }}"> --}}


    @stack('styles') {{-- Digunakan untuk CSS spesifik halaman anak yang di-push (misal: CSS preview surat di pengusulan.blade.php) --}}
</head>
<body>
    {{-- Global Success/Error Alert untuk layout aplikasi (setelah login) --}}
    {{-- Alert ini akan muncul di bagian atas viewport untuk semua halaman yang meng-extend layout ini --}}
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

    {{-- Wrapper utama untuk sidebar dan konten --}}
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('include.sidebar_pengusul')

        <!-- Page Content Wrapper (konten utama yang bergeser) -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Navbar -->
            @include('include.navbar')

            <!-- Content Area (dengan padding untuk menghindari tumpang tindih dengan navbar fixed) -->
            <main class="py-4"> {{-- Memberikan padding vertikal agar konten tidak tertutup navbar --}}
                @yield('content') {{-- Konten halaman spesifik akan dirender di sini --}}
            </main>
        </div>
    </div>

    <!-- PENTING: URUTAN LOADING SCRIPT -->

    <!-- 1. jQuery (Dibutuhkan oleh DataTables, Date Range Picker, dan banyak plugin JS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- 2. Moment.js (Diperlukan oleh Date Range Picker) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <!-- 3. Bootstrap Bundle JS (Mencakup Popper.js, esensial untuk fungsionalitas Bootstrap seperti dropdown, modal, dll.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 4. DataTables JS (Harus dimuat setelah jQuery dan Bootstrap JS) -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- 5. Date Range Picker JS (Harus dimuat setelah Moment.js dan jQuery) -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- 6. SweetAlert2 JS (Untuk pop-up alert yang lebih cantik) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Global JavaScript untuk Toggle Sidebar dan Responsivitas Layout -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-btn');
            const sidebar = document.getElementById('sidebar');
            const pageContentWrapper = document.getElementById('page-content-wrapper');

            // Memastikan elemen-elemen ada sebelum menambahkan event listener
            if (toggleBtn && sidebar && pageContentWrapper) {
                // Event listener untuk tombol toggle sidebar
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed'); // Mengubah lebar sidebar via CSS

                    // Logika khusus untuk mobile (layar kecil)
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('active'); // Mengaktifkan efek slide-in/out sidebar di mobile
                        // pageContentWrapper tidak perlu diubah transform-nya di JS, cukup CSS saja
                    }
                });

                // Event listener untuk perubahan ukuran window (responsivitas)
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        // Jika kembali ke tampilan desktop, pastikan sidebar mobile tidak aktif
                        sidebar.classList.remove('active');
                        // Pastikan pageContentWrapper tidak memiliki transform dari mobile
                        // CSS akan mengatur margin-left secara otomatis berdasarkan .sidebar.collapsed
                    }
                    // Di mobile, pengaturan margin-left adalah 0, pergeseran pakai transform oleh CSS
                });

                // Menutup sidebar mobile saat mengklik di luar sidebar atau tombol toggle
                document.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768 && // Hanya berlaku di mobile
                        sidebar.classList.contains('active') && // Jika sidebar sedang aktif (terbuka)
                        !sidebar.contains(event.target) && // Dan klik bukan di dalam sidebar
                        !toggleBtn.contains(event.target)) { // Dan klik bukan di tombol toggle
                        sidebar.classList.remove('active'); // Sembunyikan sidebar mobile
                    }
                });
            } else {
                // Peringatan jika ada elemen layout penting yang tidak ditemukan
                console.warn('Sidebar toggle elements or page content wrapper not found. Layout interactivity might be affected.');
            }
        });
    </script>

    @stack('scripts') {{-- Digunakan untuk skrip kustom dari halaman anak (view yang meng-extend layout ini) --}}
</body>
</html>