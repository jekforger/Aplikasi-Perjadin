<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Penting: Import model User

class LoginController extends Controller
{
    // Method untuk menampilkan halaman "Pilih Role"
    public function showSelectRoleForm()
    {
        return view('auth.select-role');
    }

    // Method untuk menampilkan form login (email & password)
    public function showLoginForm(Request $request)
    {
        $role = $request->query('role'); // Ambil parameter 'role' dari URL

        // Daftar role yang diizinkan (pastikan sesuai dengan data di DB)
        $allowedRoles = ['pengusul', 'pelaksana', 'bku', 'wadir', 'direktur', 'admin'];

        // Validasi sederhana: pastikan role yang dipilih ada dalam daftar yang diizinkan
        if (!in_array($role, $allowedRoles)) {
            return redirect()->route('login.select-role')->with('error', 'Pilihan role tidak valid.');
        }

        // Kirim nilai role ke view login
        return view('auth.login', ['role' => $role]);
    }

    // Method untuk memproses login form
    public function login(Request $request)
    {
        // Aturan validasi input
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'ends_with:@polban.ac.id'], // Validasi email instansi
            'password' => ['required', 'string'],
            'role' => ['required', 'string'], // Pastikan role juga dikirim dari form
        ]);

        $credentials = $request->only('email', 'password');
        $chosenRole = $request->role; // Role yang dipilih pengguna di halaman awal

        // Coba lakukan autentikasi
        if (Auth::attempt($credentials)) {
            $user = Auth::user(); // Dapatkan user yang baru saja login

            // Validasi role: Pastikan role yang dipilih di halaman awal cocok dengan role user di database
            // Untuk Wadir/Direktur yang bisa jadi Pelaksana:
            // Mereka akan login sebagai Wadir/Direktur utama dulu.
            // Fungsi "beralih role" akan ada di dalam dashboard mereka nanti.
            if ($user->role !== $chosenRole) {
                Auth::logout(); // Jika role tidak cocok, logout user
                $request->session()->invalidate(); // Hancurkan sesi
                $request->session()->regenerateToken(); // Regenerate token

                // Kirim pesan error kembali ke halaman login
                throw ValidationException::withMessages([
                    'email' => ['Role yang Anda pilih tidak cocok dengan akun ini. Silakan pilih role yang sesuai.'],
                ]);
            }

            // Jika berhasil dan role cocok, generate ulang sesi
            $request->session()->regenerate();

            // Redirect user ke dashboard yang sesuai dengan role-nya
            return $this->redirectToRoleDashboard($chosenRole);
        }

        // Jika autentikasi gagal (email/password salah)
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')], // Pesan standar Laravel untuk kredensial salah
        ]);
    }

    // Method untuk mengarahkan user setelah login berhasil
    protected function redirectToRoleDashboard($role)
    {
        switch ($role) {
            case 'pengusul':
                return redirect()->route('pengusul.dashboard');
            case 'pelaksana':
                return redirect()->route('pelaksana.dashboard');
            case 'bku':
                return redirect()->route('bku.dashboard');
            case 'wadir':
                return redirect()->route('wadir.dashboard');
            case 'direktur':
                return redirect()->route('direktur.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
                // Jika role tidak dikenal, arahkan ke halaman pilih role
                return redirect()->route('login.select-role')->with('error', 'Role tidak dikenal.');
        }
    }

    // Method untuk logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.select-role'); // Kembali ke halaman "Pilih Role"
    }
}