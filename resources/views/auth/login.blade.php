<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/polban2.png') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Aplikasi Perjalanan Dinas</title>
</head>
<body>
<div class="container-fluid full-height">
    <div class="row full-height">
        <!-- Bagian Kiri (Form Login) -->
        <div class="col-md-4 d-flex align-items-center justify-content-center left-side">
            <div class="login-box text-center">
                <img src="img/polban.png" alt="POLBAN Logo" width="100">
                <h4 class="mt-3">Aplikasi Perjalanan Dinas<br>Politeknik Negeri Bandung</h4>
                <p class="text-muted">Login</p>

                <form>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email Pengguna" required>
                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" placeholder="Masukkan Password" required>
                        <span class="input-group-text">
                            <i class="bi bi-lock"></i>
                        </span>
                    </div>
                </div>
                    <div class="text-end">
                        <a href="#" class="text-decoration-none">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn btn-orange w-100 mt-3">Login</button>
                    <div class="mt-3">
                        <p class="mb-0">Belum punya akun? <a href="#" class="text-decoration-none">Daftar</a></p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Bagian Kanan (Ilustrasi) -->
        <div class="col-md-8 right-side">
            <img src="img/login.png" class="img-fluid" alt="Illustration">
        </div>
    </div>
</div>
</body>
</html>
