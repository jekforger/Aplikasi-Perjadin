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

        // Data Statistik Dashboard (4 Kotak sesuai Desain)
        $dashboardStats = [
            'total_pengusulan' => $faker->numberBetween(100, 500),
            'usulan_baru' => $faker->numberBetween(0, 20),
            'dalam_proses_direktur' => $faker->numberBetween(0, 10),
            'bertugas' => $faker->numberBetween(0, 5),
        ];

        // Data Dummy untuk Tabel Detail Pengusulan (Sesuai Desain)
        $pengusulanDetails = [];
        $statuses = ['Pending', 'Disetujui', 'Ditolak', 'Selesai']; // Tambahkan 'Selesai'
        $sumberDanaOptions = ['RM', 'PNBP', 'DIPA', 'Mandiri']; // Mengganti pembiayaan jadi sumber dana
        $nomorSuratPrefixes = ['625/KO/RT.01.00/', '625/KU/SK.02.01/', '625/PU/SP.03.02/'];

        for ($i = 1; $i <= 10; $i++) { // Membuat 10 data dummy
            $tanggalPengusulan = $faker->dateTimeBetween('-3 months', '-1 month');
            $tanggalBerangkat = $faker->dateTimeBetween($tanggalPengusulan, $tanggalPengusulan->format('Y-m-d') . ' + 2 weeks');

            $pengusulanDetails[] = [
                'no' => $i,
                'tgl_pengusulan' => $tanggalPengusulan->format('d M Y'), // Tanggal Pengusulan
                'tgl_berangkat' => $tanggalBerangkat->format('d M Y'),
                'tgl_pulang' => $faker->dateTimeBetween($tanggalBerangkat, $tanggalBerangkat->format('Y-m-d') . ' + 7 days')->format('d M Y'),
                'nomor_surat_pengusulan' => $faker->randomElement($nomorSuratPrefixes) . $faker->year(), // Nomor Surat Pengusulan
                'sumber_dana' => $faker->randomElement($sumberDanaOptions), // Sumber Dana
                'pembiayaan' => $faker->randomElement($sumberDanaOptions), // Juga untuk pembiayaan, jika dipakai di tempat lain
                'status' => $faker->randomElement($statuses),
                'action' => 'View'
            ];
        }

        $roleDisplayName = 'Wakil Direktur';
        if (\Auth::check()) {
            $loginController = new LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName(\Auth::user()->role);
        }

        return view('layouts.Wadir.dashboard', compact('dashboardStats', 'pengusulanDetails', 'roleDisplayName'));
    }
}