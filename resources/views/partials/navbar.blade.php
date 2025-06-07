{{-- resources/views/partials/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light"> {{-- Hapus bg-light border-bottom jika sudah diatur di main.css --}}
    <div class="container-fluid">
        {{-- Hapus toggle button bawaan Bootstrap jika toggle sidebar sudah diganti --}}
        {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> --}}

        <div class="ms-auto"> {{-- Menggeser ke kanan --}}
            <div class="d-flex align-items-center">
                @auth
                    <div class="dropdown user-info">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random&color=fff" alt="User Avatar" class="rounded-circle me-2" width="30" height="30">
                            <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Guest' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownUser">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @php
                        $loginController = new \App\Http\Controllers\Auth\LoginController();
                        $userRoleDisplay = $loginController->getRoleDisplayName(Auth::user()->role ?? '');
                    @endphp
                    <span class="badge bg-secondary ms-2">{{ $userRoleDisplay }}</span>
                @else
                    <a href="{{ route('login.select-role') }}" class="btn btn-outline-primary me-2">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>