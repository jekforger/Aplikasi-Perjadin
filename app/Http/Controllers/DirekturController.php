<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use Faker\Factory as Faker;

class DirekturController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Direktur.
     */
    public function dashboard()
    {
        $faker = Faker::create('id_ID');

        // Data Dummy untuk Kotak Dashboard Direktur
        $dashboardStats = [
            'total_pengusulan' => $faker->numberBetween(200, 1000), // Angka random
            'dalam_proses_wadir' => $faker->numberBetween(10, 50), // Angka random
            'dalam_proses_bku' => $faker->numberBetween(5, 25), // Angka random
            'bertugas' => $faker->numberBetween(0, 10), // Angka random
        ];

        // Data Dummy untuk Tabel Detail Pengusulan (untuk Direktur)
        $pengusulanDetails = [];
        $statuses = ['Disetujui Wadir', 'Ditolak Wadir', 'Sedang Diproses', 'Selesai']; // Status khusus Direktur
        $sumberDanaOptions = ['RM', 'PNBP', 'DIPA', 'Mandiri'];
        $nomorSuratPrefixes = ['625/KO/RT.01.00/', '625/KU/SK.02.01/', '625/PU/SP.03.02/'];

        for ($i = 1; $i <= 10; $i++) { // Membuat 10 data dummy
            $tanggalBerangkat = $faker->dateTimeBetween('-2 months', '-1 week');

            $pengusulanDetails[] = [
                'no' => $i,
                'tgl_berangkat' => $tanggalBerangkat->format('d M Y'),
                'nomor_surat' => $faker->randomElement($nomorSuratPrefixes) . $faker->year(), // Nomor Surat
                'sumber_dana' => $faker->randomElement($sumberDanaOptions),
                'status' => $faker->randomElement($statuses),
            ];
        }

        $roleDisplayName = 'Direktur';
        $userRole = null;
        if (\Auth::check()) {
            $userRole = \Auth::user()->role;
            $loginController = new LoginController();
            $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        }

        return view('layouts.direktur.dashboard', compact('dashboardStats', 'pengusulanDetails', 'roleDisplayName', 'userRole'));
    }

    public function persetujuan() {
        return view('layouts.direktur.persetujuan');
    }
}