<nav class="navbar navbar-light bg-light px-4 py-2 shadow-sm">
  <div class="container-fluid justify-content-end">
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
        {{-- Menggunakan Auth::user()->name untuk gambar avatar dan nama --}}
        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random&color=fff" alt="User" width="32" height="32" class="rounded-circle me-2">
        <div class="text-start">
          <div class="fw-bold text-dark">{{ Auth::user()->name ?? 'Guest' }}</div>
          {{-- Menampilkan role dinamis --}}
          <small class="text-muted">
            @php
                // Pastikan LoginController di-resolve untuk mengakses getRoleDisplayName
                $loginController = app(\App\Http\Controllers\Auth\LoginController::class);
                echo $loginController->getRoleDisplayName(Auth::user()->role ?? '');
            @endphp
          </small>
        </div>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
        <li><a class="dropdown-item" href="#">Change Password</a></li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">Logout</button>
            </form>
        </li>
      </ul>
    </div>
  </div>
</nav>