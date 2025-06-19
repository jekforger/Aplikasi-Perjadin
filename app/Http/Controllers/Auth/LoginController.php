<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User; // Penting: Import model User

class LoginController extends Controller
{
    // Array mapping untuk nama role agar user-friendly
    // Inilah yang mendefinisikan role-role yang valid dan namanya di UI
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
        // Mengembalikan nama tampilan jika ada, jika tidak, format dari key (contoh: 'wadir_1' menjadi 'Wadir 1')
        return $this->roleDisplayNames[$roleKey] ?? ucwords(str_replace('_', ' ', $roleKey));
    }

    /**
     * Mengambil key role dari display name (jika diperlukan reverse lookup).
     * Tidak langsung digunakan di alur login ini, tapi baik untuk ada.
     */
    public function getRoleKeyFromDisplayName($displayName)
    {
        return array_search($displayName, $this->roleDisplayNames);
    }


    // Method untuk menampilkan halaman "Pilih Role"
    public function showSelectRoleForm()
    {
        // Mengirim daftar role yang valid ke view (untuk dropdown)
        // Kita tidak akan langsung mengirim $roleDisplayNames ke view,
        // karena view select-role sudah memiliki hardcoded options.
        // Cukup biarkan view select-role yang mengelola pilihannya.
        return view('auth.select-role');
    }

    // Method untuk menampilkan form login (email & password)
    public function showLoginForm(Request $request)
    {
        $role = $request->query('role'); // Ambil parameter 'role' dari URL

        // Daftar role yang diizinkan diambil dari keys array roleDisplayNames
        $allowedRoles = array_keys($this->roleDisplayNames);

        // Validasi sederhana: pastikan role yang dipilih ada dalam daftar yang diizinkan
        if (!in_array($role, $allowedRoles)) {
            return redirect()->route('login.select-role')->with('error', 'Pilihan role tidak valid.');
        }

        // Dapatkan nama tampilan role untuk ditampilkan di UI login
        $displayName = $this->getRoleDisplayName($role);

        // Kirim nilai role dan display name ke view login
        return view('auth.login', [
            'role' => $role,
            'displayName' => $displayName // Pastikan ini ada dan variabelnya benar di view login
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
            // Perlu diperiksa juga apakah role user dari DB adalah role yang diizinkan secara umum.
            $allowedRoles = array_keys($this->roleDisplayNames); // Ambil dari daftar role yang valid
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

            // Kirim pesan sukses ke sesi untuk ditampilkan di dashboard
            $displayName = $this->getRoleDisplayName($chosenRole);
            $request->session()->flash('success_message', 'Selamat datang, ' . $displayName);

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
        // Kita bisa mengelompokkan semua role Wadir karena mereka diarahkan ke dashboard yang sama.
        if (str_starts_with($role, 'wadir_')) {
            return redirect()->route('wadir.dashboard');
        }

        switch ($role) {
            case 'pengusul':
                return redirect()->route('pengusul.dashboard');
            case 'pelaksana':
                return redirect()->route('pelaksana.dashboard');
            case 'bku':
                return redirect()->route('bku.dashboard');
            // case 'wadir': // Baris ini tidak lagi diperlukan jika menggunakan wadir_1, wadir_2, dst.
            //    return redirect()->route('wadir.dashboard');
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