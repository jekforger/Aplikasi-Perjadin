<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('include.style_pengusul')
    <title>Pengusulan</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('include.sidebar_pengusul')

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
</body>
</html>