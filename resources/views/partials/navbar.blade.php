{{-- resources/views/partials/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        {{-- Logo/Nama Aplikasi untuk Mobile (akan disembunyikan di desktop oleh CSS) --}}
        <a class="navbar-brand me-auto ms-3 d-lg-none" href="#"> 
            <img src="{{ asset('images/polban_logo.png') }}" alt="Polban Logo" height="30" class="d-inline-block align-text-top me-2">
            Aplikasi SPPD Polban
        </a>

        {{-- Bagian User Info di Kanan --}}
        <div class="ms-auto">
            <div class="d-flex align-items-center">
                @auth {{-- Tampilkan hanya jika user sudah login --}}
                    <div class="dropdown user-info">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-profile-toggle position-relative pe-4" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random&color=fff" alt="User Avatar" class="rounded-circle me-2" width="30" height="30">
                            
                            <div class="user-info-text d-none d-md-block">
                                <span class="user-name">{{ Auth::user()->name ?? 'Guest' }}</span><br>
                                @php
                                    $loginController = new \App\Http\Controllers\Auth\LoginController(); // Instansiasi di sini
                                    $userRoleDisplay = $loginController->getRoleDisplayName(Auth::user()->role ?? '');
                                @endphp
                                <small class="user-role-display">{{ $userRoleDisplay }}</small>
                            </div>
                            
                            <i class="bi bi-chevron-down navbar-profile-chevron"></i> 
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="{{ route('user.change-password.form') }}">Change Password</a></li>
                            <li>
                                @csrf
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                @else {{-- Jika belum login --}}
                    <a href="{{ route('login.select-role') }}" class="btn btn-outline-primary me-2">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>