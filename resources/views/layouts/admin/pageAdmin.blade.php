<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('include.admin.style_admin')
    <title>Admin</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('include.admin.sidebar_admin')

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Navbar -->
            @include('include.navbar')

            <!-- Content Area -->
            <div class="container-fluid mt-4">
                @yield('content')
            </div>
        </div>
    </div>

    @include('include.script')

    <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS & Popper.js (letakkan sebelum closing body tag) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>