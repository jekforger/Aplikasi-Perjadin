{{-- resources/views/wadir/partials/sidebar.blade.php --}}

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('img/polban2.png') }}" alt="Polban Logo" class="sidebar-logo">
        <div class="sidebar-title">
            <span class="app-name">Aplikasi SPPD</span>
            <span class="institution-name">Polban</span>
        </div>
    </div>
    <nav class="nav flex-column">
        {{-- Dashboard Link --}}
        <a href="{{ route('wadir.dashboard') }}" class="nav-link {{ Request::routeIs('wadir.dashboard') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-columns-gap"></i>
            </span>
            <span class="description">Dashboard</span>
        </a>
        {{-- Persetujuan Link (mengarah ke halaman persetujuan) --}}
        <a href="{{ route('wadir.persetujuan') }}" class="nav-link {{ Request::routeIs('wadir.persetujuan') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-check2-square"></i>
            </span>
            <span class="description">Persetujuan</span>
        </a>

        {{-- Ganti Role Link (dengan Submenu) --}}
        <a href="#submenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="submenu">
            <span class="icon">
                <i class="bi bi-arrow-repeat"></i>
            </span>
            <span class="description">Ganti Role <i class="bi bi-caret-down"></i></span>
        </a>

        <div class="sub-menu collapse" id="submenu">
            <a href="{{ route('wadir.dashboard') }}" class="nav-link sub-nav-link">
                <span class="description">
                    Wakil Direktur I
                </span>
            </a>
            <a href="{{ route('pelaksana.dashboard') }}" class="nav-link sub-nav-link">
                <span class="description">
                    Pelaksana
                </span>
            </a>
        </div>
    </nav>
</div>

<button id="toggle-btn" class="toggle-btn">
    <i class="bi bi-list"></i>
</button>