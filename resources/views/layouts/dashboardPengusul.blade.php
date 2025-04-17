<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboardPengusul.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/polban2.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Pengusul</title>
</head>
<body>
    <!-- Toggle Button - Dipindah ke luar sidebar -->
    <button class="toggle-btn" id="toggle-btn">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('img/polban2.png') }}" alt="Polban Logo" class="sidebar-logo">
            <div class="sidebar-title">
                <span class="app-name">Aplikasi SPPD</span>
                <span class="institution-name">Polban</span>
            </div>
        </div>
        <nav class="nav flex-column">
            <a href="#" class="nav-link">
                <span class="icon">
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="description">Dashboard</span>
            </a>
            <a href="#" class="nav-link active">
                <span class="icon">
                    <i class="bi bi-file-earmark-medical"></i>
                </span>
                <span class="description">Pengusulan</span>
            </a>
            <a href="#" class="nav-link">
                <span class="icon">
                    <i class="bi bi-graph-up"></i>
                </span>
                <span class="description">Status</span>
            </a>
            <a href="#" class="nav-link">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i>
                </span>
                <span class="description">Draft</span>
            </a>
            <a href="#" class="nav-link">
                <span class="icon">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </span>
                <span class="description">History</span>
            </a>
        </nav>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-btn');
            const sidebar = document.getElementById('sidebar');
            
            toggleBtn.addEventListener('click', function() {
                const isCollapsed = sidebar.classList.toggle('collapsed');
                const icon = this.querySelector('i');
                
                // Perbaikan: Tukar kondisi icon
                if (isCollapsed) {
                    icon.classList.remove('bi-x');
                    icon.classList.add('bi-list');
                } else {
                    icon.classList.remove('bi-list');
                    icon.classList.add('bi-x');
                }
                
                // Sesuaikan posisi tombol
                this.style.left = isCollapsed ? '95px' : '265px';
            });
            
            // Inisialisasi untuk mobile
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('bi-list');
                icon.classList.add('bi-x');
                toggleBtn.style.left = '15px';
            }
        });
    </script>
</body>
</html>