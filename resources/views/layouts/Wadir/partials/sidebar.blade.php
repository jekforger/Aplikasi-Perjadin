{{-- resources/views/wadir/partials/sidebar.blade.php --}}

<div class="sidebar" id="sidebar"> {{-- Hapus class="sticky-top" jika ada --}}
    <div class="sidebar-header">
        <img src="{{ asset('img/polban2.png') }}" alt="Polban Logo" class="sidebar-logo">
        <div class="sidebar-title">
            <span class="app-name">Aplikasi SPPD</span>
            <span class="institution-name">Polban</span>
        </div>
    </div>
    <nav class="nav flex-column">
        {{-- Dashboard Link --}}
        <a href="{{ route('wadir.dashboard') }}" class="nav-link {{ Request::is('wadir/dashboard*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-columns-gap"></i>
            </span>
            <span class="description">Dashboard</span>
        </a>
        {{-- Persetujuan Link --}}
        <a href="#" class="nav-link {{ Request::is('wadir/persetujuan*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-check2-square"></i>
            </span>
            <span class="description">Persetujuan</span>
        </a>
        {{-- Paraf Link --}}
        <a href="#" class="nav-link {{ Request::is('wadir/paraf*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-pen"></i>
            </span>
            <span class="description">Paraf</span>
        </a>
        
        {{-- Ganti Role Link (dengan Submenu) --}}
        <a href="#submenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="submenu">
            <span class="icon">
                <i class="bi bi-arrow-repeat"></i>
            </span>
            <span class="description">Ganti Role <i class="bi bi-caret-down"></i></span>
        </a>

        <div class="sub-menu collapse" id="submenu">
            <a href="{{ route('wadir.dashboard') }}" class="nav-link sub-nav-link"> {{-- Link ke dashboard Wadir default --}}
                <span class="description">
                    {{ $roleDisplayName }}
                </span>
            </a>
            <a href="{{ route('pelaksana.dashboard') }}" class="nav-link sub-nav-link"> {{-- Link ke dashboard Pelaksana --}}
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