<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Menampilkan form untuk mengubah password.
     */
    public function showChangePasswordForm()
    {
        // Layout yang akan digunakan tergantung role yang login.
        // Kita akan menggunakan layout utama aplikasi (main.blade.php)
        // karena ini adalah halaman pengaturan profil user.
        return view('user.change-password');
    }

    /**
     * Memproses perubahan password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password lama tidak sesuai.',
            ]);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Simpan role sebelum logout
        $role = $user->role;
        // Session::flash('success', 'Password berhasil diubah! Silakan login kembali menggunakan password baru.');
        Auth::logout();

        // Redirect ke login sesuai role (pakai query string, cukup satu route login)
        return redirect()->route('login.form', ['role' => $role])->with('success', 'Password berhasil diubah. Silakan login kembali menggunakan password baru.');
    }
}