<!-- Sidebar -->
<div class="sidebar" class="sticky-top" id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('img/polban2.png') }}" alt="Polban Logo" class="sidebar-logo">
        <div class="sidebar-title">
            <span class="app-name">Aplikasi SPPD</span>
            <span class="institution-name">Polban</span>
        </div>
    </div>
        <nav class="nav flex-column">
            <a href="{{ route('bku.dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-columns-gap"></i>
                </span>
                <span class="description">Dashboard</span>
            </a>
            <a href="{{ route('bku.laporan') }}" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i> 
                </span>
                <span class="description">Laporan & Bukti Perjalanan Dinas</span>
            </a>
        </nav>
    </div>

<!-- Toggle Button -->
<button id="toggle-btn" class="toggle-btn">
    <i class="bi bi-list"></i>
</button>
