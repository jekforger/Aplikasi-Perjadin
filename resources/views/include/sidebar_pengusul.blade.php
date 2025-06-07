<!-- Toggle Button -->
<button id="toggle-btn" class="toggle-btn">
    <i class="bi bi-list"></i>
</button>

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
            <a href="{{ route('pengusul.dashboard') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="description">Dashboard</span>
            </a>
            <a href="{{ route('pengusul.pengusulan') }}" class="nav-link {{ request()->is('pengusulan') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark-medical"></i>
                </span>
                <span class="description">Pengusulan</span>
            </a>
            <a href="{{ route('pengusul.status') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-graph-up"></i>
                </span>
                <span class="description">Status</span>
            </a>
            <a href="{{ route('pengusul.draft') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i>
                </span>
                <span class="description">Draft</span>
            </a>
            <a href="{{ route('pengusul.history') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </span>
                <span class="description">History</span>
            </a>
        </nav>
    </div>