<!-- Toggle Button -->
<button id="toggle-btn" class="toggle-btn">
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
            {{-- Menggunakan request()->routeIs() untuk penentuan kelas 'active' --}}
            <a href="{{ route('pengusul.dashboard') }}" class="nav-link {{ request()->routeIs('pengusul.dashboard') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="description">Dashboard</span>
            </a>
            <a href="{{ route('pengusul.pengusulan') }}" class="nav-link {{ request()->routeIs('pengusul.pengusulan') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark-medical"></i>
                </span>
                <span class="description">Pengusulan</span>
            </a>
            <a href="{{ route('pengusul.status') }}" class="nav-link {{ request()->routeIs('pengusul.status') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-graph-up"></i>
                </span>
                <span class="description">Status</span>
            </a>
            <a href="{{ route('pengusul.draft') }}" class="nav-link {{ request()->routeIs('pengusul.draft') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i>
                </span>
                <span class="description">Draft</span>
            </a>
            <a href="{{ route('pengusul.history') }}" class="nav-link {{ request()->routeIs('pengusul.history') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </span>
                <span class="description">History</span>
            </a>
        </nav>
    </div>