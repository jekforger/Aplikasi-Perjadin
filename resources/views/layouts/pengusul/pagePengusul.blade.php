<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('include.style_pengusul')
    <title>Pengusulan</title>
</head>
<body>
        @include('include.sidebar_pengusul')

    <div class="container-fluid">
        
        <!-- User Info Section -->
        
        <!-- User Avatar (opsional) -->
        <div class="dropdown">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://ui-avatars.com/api/?name=Naufal+Syafiq&background=random" alt="User" width="40" height="40" class="rounded-circle">
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-down"></i> Pengaturan</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
        </div>
        </div>
    </div>
    </nav>

    @yield('content')

    @include('include.script')
</body>
</html>