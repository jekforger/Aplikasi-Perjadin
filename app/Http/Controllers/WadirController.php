<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use Faker\Factory as Faker;

class WadirController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Wakil Direktur.
     */
    public function dashboard()
    {
        $faker = Faker::create('id_ID');

        // Data Statistik Dashboard (Angka Random menggunakan Faker)
        // Disesuaikan agar hanya 3 kotak seperti desain
        $dashboardStats = [
            'usulan_baru' => $faker->numberBetween(0, 20),      // Angka random
            'dalam_proses_direktur' => $faker->numberBetween(0, 10), // Angka random
            'bertugas' => $faker->numberBetween(0, 5),          // Angka random
        ];

        // Data Dummy untuk Tabel Detail Pengusulan (Menggunakan Faker)
        $pengusulanDetails = [];
        $statuses = ['Pending', 'Disetujui', 'Ditolak', 'Selesai']; // Berbagai status yang mungkin
        $pembiayaans = ['Polban', 'Penyelenggara', 'Polban dan Penyelenggara'];

        for ($i = 1; $i <= 15; $i++) {
            $startDate = $faker->dateTimeBetween('-1 month', '+3 months');
            $endDate = $faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' + 7 days');

            $pengusulanDetails[] = [
                'no' => $i,
                // 'nama_kegiatan' => $faker->sentence(mt_rand(4, 8)), // Dihapus dari tabel, jadi tidak perlu di sini
                'tgl_berangkat' => $startDate->format('d/m/Y'),
                'tgl_pulang' => $endDate->format('d/m/Y'),
                'pembiayaan' => $faker->randomElement($pembiayaans),
                'status' => $faker->randomElement($statuses),
                'action' => 'View'
            ];
        }

        // Mendapatkan display name dari role yang login untuk tampilan di dashboard
        $roleDisplayName = 'Wakil Direktur'; // Default jika tidak login
        if (\Auth::check()) {
            $loginController = new LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName(\Auth::user()->role);
        }

        return view('layouts.Wadir.dashboard', compact('dashboardStats', 'pengusulanDetails', 'roleDisplayName'));
    }
}