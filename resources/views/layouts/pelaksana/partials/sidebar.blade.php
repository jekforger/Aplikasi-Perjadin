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
            <a href="{{ route('pelaksana.dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-columns-gap"></i>
                </span>
                <span class="description">Dashboard</span>
            </a>
            <a href="{{ route('pelaksana.bukti') }}" class="nav-link {{ request()->is('bukti') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i>
                </span>
                <span class="description">Laporan & Bukti Perjalanan Dinas</span>
            </a>
            <!-- <a href="{{ route('pelaksana.laporan') }}" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark"></i>
                </span>
                <span class="description">Laporan</span>
            </a>
            <a href="{{ route('pelaksana.dokumen') }}" class="nav-link {{ request()->is('dokumen') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-file-earmark-text"></i>
                </span>
                <span class="description">Dokumen</span>
            </a> -->
            <a href="{{ route('pelaksana.statusLaporan') }}" class="nav-link {{ request()->is('statusLaporan') ? 'active' : '' }}">
                <span class="icon">
                    <i class="bi bi-list-check"></i>
                </span>
                <span class="description">Status Laporan</span>
            </a>

            @php
            $rolesWithSwitch = ['wadir_1', 'wadir_2', 'wadir_3', 'wadir_4', 'direktur'];
            @endphp
            
            @if (isset($userRole) && in_array($userRole, $rolesWithSwitch))
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

<!-- Toggle Button -->
<button id="toggle-btn" class="toggle-btn">
    <i class="bi bi-list"></i>
</button>
