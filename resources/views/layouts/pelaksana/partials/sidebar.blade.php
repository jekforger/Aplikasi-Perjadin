{{-- resources/views/pelaksana/partials/sidebar.blade.php --}}

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
        <a href="{{ route('pelaksana.dashboard') }}" class="nav-link {{ Request::is('pelaksana/dashboard*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-columns-gap"></i>
            </span>
            <span class="description">Dashboard</span>
        </a>
        {{-- Bukti Link --}}
        <a href="#" class="nav-link {{ Request::is('pelaksana/bukti*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-card-image"></i>
            </span>
            <span class="description">Bukti</span>
        </a>
        {{-- Laporan Link --}}
        <a href="#" class="nav-link {{ Request::is('pelaksana/laporan*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-file-earmark"></i>
            </span>
            <span class="description">Laporan</span>
        </a>
        {{-- Dokumen Link --}}
        <a href="#" class="nav-link {{ Request::is('pelaksana/dokumen*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-file-earmark-text"></i>
            </span>
            <span class="description">Dokumen</span>
        </a>
        {{-- Status Laporan Detail Link --}}
        <a href="#" class="nav-link {{ Request::is('pelaksana/status-laporan-detail*') ? 'active' : '' }}">
            <span class="icon">
                <i class="bi bi-list-check"></i>
            </span>
            <span class="description">Status Laporan</span>
        </a>
        
        @php
            // Daftar role yang diizinkan untuk melihat fitur "Ganti Role"
            $rolesWithSwitch = ['wadir_1', 'wadir_2', 'wadir_3', 'wadir_4', 'direktur'];
        @endphp
        
        @if (isset($userRole) && in_array($userRole, $rolesWithSwitch)) {{-- PASTIKAN @IF INI MEMBUNGKUS SELURUH BLOK --}}
            <a href="#submenu" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="submenu">
                <span class="icon">
                    <i class="bi bi-arrow-repeat"></i>
                </span>
                <span class="description">Ganti Role <i class="bi bi-caret-down"></i></span>
            </a>

            <div class="sub-menu collapse" id="submenu">
                <a href="{{ route('wadir.dashboard') }}" class="nav-link sub-nav-link">
                    <span class="description">
                        {{ $roleDisplayName }}
                    </span>
                </a>
                <a href="{{ route('pelaksana.dashboard') }}" class="nav-link sub-nav-link">
                    <span class="description">
                        Pelaksana
                    </span>
                </a>
            </div>
        @endif
    </nav>
</div>

<button id="toggle-btn" class="toggle-btn">
    <i class="bi bi-list"></i>
</button>