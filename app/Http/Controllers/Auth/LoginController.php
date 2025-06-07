<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    // Array mapping untuk nama role agar user-friendly
    protected $roleDisplayNames = [
        'pengusul' => 'Pengusul',
        'pelaksana' => 'Pelaksana',
        'bku' => 'Badan Keuangan Umum (BKU)',
        'wadir_1' => 'Wakil Direktur I',
        'wadir_2' => 'Wakil Direktur II',
        'wadir_3' => 'Wakil Direktur III',
        'wadir_4' => 'Wakil Direktur IV',
        'direktur' => 'Direktur',
        'sekdir' => 'Sekretaris Direktur',
        'admin' => 'Admin',
    ];

    /**
     * Mengambil nama role yang user-friendly.
     */
    public function getRoleDisplayName($roleKey)
    {
        return $this->roleDisplayNames[$roleKey] ?? ucwords(str_replace('_', ' ', $roleKey));
    }

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
        $allowedRoles = array_keys($this->roleDisplayNames);

        // Validasi sederhana: pastikan role yang dipilih ada dalam daftar yang diizinkan
        if (!in_array($role, $allowedRoles)) {
            return redirect()->route('login.select-role')->with('error', 'Pilihan role tidak valid.');
        }

        // --- INI BARIS YANG HARUS DIHILANGKAN KOMENTARNYA ---
        $displayName = $this->getRoleDisplayName($role);

        // Kirim nilai role ke view login
        // --- DAN PASTIKAN $displayName DIKIRIM KE VIEW DI SINI ---
        return view('auth.login', [
            'role' => $role,
            'displayName' => $displayName // Pastikan ini ada dan variabelnya benar
        ]);
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
            $allowedRoles = array_keys($this->roleDisplayNames);

            if (!in_array($user->role, $allowedRoles)) {
                 Auth::logout();
                 $request->session()->invalidate();
                 $request->session()->regenerateToken();
                 throw ValidationException::withMessages([
                     'email' => ['Akun Anda tidak memiliki role yang valid. Silakan hubungi administrator.'],
                 ]);
            }

            if ($user->role !== $chosenRole) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => ['Role yang Anda pilih tidak cocok dengan akun ini. Anda terdaftar sebagai ' . $this->getRoleDisplayName($user->role) . '.'], // <-- Tampilkan nama user-friendly di sini
                ]);
            }

            // Jika berhasil dan role cocok, generate ulang sesi
            $request->session()->regenerate();
            
            $displayName = $this->getRoleDisplayName($chosenRole); // Ambil nama tampilan role yang baru login
            $request->session()->flash('success_message', 'Selamat datang, ' . $displayName); // Kirim pesan ke sesi

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
        // Sesuaikan rute dashboard untuk setiap role, termasuk Wadir I-IV
        switch ($role) {
            case 'pengusul':
                return redirect()->route('pengusul.dashboard');
            case 'pelaksana':
                return redirect()->route('pelaksana.dashboard');
            case 'bku':
                return redirect()->route('bku.dashboard');
            case 'wadir_1':
            case 'wadir_2':
            case 'wadir_3':
            case 'wadir_4':
                return redirect()->route('wadir.dashboard');
            case 'direktur':
                return redirect()->route('direktur.dashboard');
            case 'sekdir':
                return redirect()->route('sekdir.dashboard');
            case 'admin':
                return redirect()->route('admin.dashboard');
            default:
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