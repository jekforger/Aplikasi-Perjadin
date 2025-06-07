<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use Faker\Factory as Faker; // **TAMBAHKAN INI UNTUK MENGIMPORT FAKER**

class WadirController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Wakil Direktur.
     */
    public function dashboard()
    {
        // **INISIALISASI FAKER**
        $faker = Faker::create('id_ID'); // 'id_ID' untuk data Bahasa Indonesia (misal: nama, alamat)

        // Data Statistik Dashboard (Angka Random menggunakan Faker)
        $dashboardStats = [
            'total_pengusulan' => $faker->numberBetween(50, 200), // Angka random antara 50 dan 200
            'usulan_baru' => $faker->numberBetween(0, 20),      // Angka random antara 0 dan 20
            'dalam_proses_direktur' => $faker->numberBetween(0, 10), // Angka random antara 0 dan 10
            'bertugas' => $faker->numberBetween(0, 5),          // Angka random antara 0 dan 5
        ];

        // Data Dummy untuk Tabel Detail Pengusulan (Menggunakan Faker)
        $pengusulanDetails = [];
        $statuses = ['Pending', 'Disetujui', 'Ditolak', 'Selesai']; // Berbagai status yang mungkin
        $pembiayaans = ['Polban', 'Penyelenggara', 'Polban dan Penyelenggara']; // Berbagai opsi pembiayaan

        for ($i = 1; $i <= 15; $i++) { // Membuat 15 data dummy
            $startDate = $faker->dateTimeBetween('-1 month', '+3 months'); // Tanggal mulai dalam rentang 1 bulan lalu hingga 3 bulan ke depan
            $endDate = $faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' + 7 days'); // Tanggal selesai 0-7 hari setelah tanggal mulai

            $pengusulanDetails[] = [
                'no' => $i,
                'nama_kegiatan' => $faker->sentence(mt_rand(4, 8)), // Kalimat random (4-8 kata) untuk nama kegiatan
                'tgl_berangkat' => $startDate->format('d/m/Y'), // Format tanggal ke DD/MM/YYYY
                'tgl_pulang' => $endDate->format('d/m/Y'),     // Format tanggal
                'pembiayaan' => $faker->randomElement($pembiayaans), // Pilih random dari array pembiayaan
                'status' => $faker->randomElement($statuses),    // Pilih random dari array status
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