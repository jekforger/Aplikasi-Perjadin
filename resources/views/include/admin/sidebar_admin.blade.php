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
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <span class="icon">
                        <i class="bi bi-house-door"></i>
                    </span>
                <span class="description">Dashboard</span>
            </a>
             <a href="{{ route('admin.datapegawai') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-house-door"></i>
                </span>
                <span class="description">Pegawai</span>
            </a>
            <a href="{{ route('admin.datamahasiswa') }}" class="nav-link">
                <span class="icon">
                    <i class="bi bi-file-earmark-medical"></i>
                </span>
                <span class="description">Mahasiswa</span>
            </a>
        </nav>
    </div>