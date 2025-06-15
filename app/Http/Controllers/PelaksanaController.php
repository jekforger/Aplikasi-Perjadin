<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use Faker\Factory as Faker;

class PelaksanaController extends Controller
{
    /**
     * Menampilkan halaman dashboard untuk Pelaksana.
     */
    public function dashboard()
    {
        $faker = Faker::create('id_ID');

        // Data Dummy untuk Kotak Dashboard Pelaksana
        $dashboardStats = [
            'total_penugasan' => $faker->numberBetween(10, 50),
            'penugasan_baru' => $faker->numberBetween(0, 10),
            'laporan_belum_selesai' => $faker->numberBetween(0, 5),
        ];

        // Data Dummy untuk Tabel Detail Tugas Pelaksana
        $tugasDetails = [];
        $statusLaporan = ['Selesai', 'Belum Upload', 'Pending Revisi']; // Status laporan yang relevan
        $sumberDana = ['RM', 'PNBP']; // Contoh sumber dana
        $tanggunganBiaya = ['Polban', 'Penyelenggara', 'Polban dan Penyelenggara'];

        for ($i = 1; $i <= 10; $i++) { // Membuat 10 data dummy
            $tanggalPengusulan = $faker->dateTimeBetween('-2 months', '-1 week');
            $tanggalBerangkat = $faker->dateTimeBetween($tanggalPengusulan, $tanggalPengusulan->format('Y-m-d') . ' + 2 weeks');

            $tugasDetails[] = [
                'no' => $i,
                'tanggal_pengusulan' => $tanggalPengusulan->format('d M Y'),
                'tanggal_berangkat' => $tanggalBerangkat->format('d M Y'),
                'nomor_surat_tugas' => '625/KO/RT.' . str_pad($faker->numberBetween(1, 99), 2, '0', STR_PAD_LEFT) . '.' . $faker->year(),
                'sumber_dana' => $faker->randomElement($sumberDana),
                'status_laporan' => $faker->randomElement($statusLaporan),
                'tanggungan_biaya' => $faker->randomElement($tanggunganBiaya),
            ];
        }

        // $roleDisplayName = 'Pelaksana';
        // $userRole = null;
        // if (\Auth::check()) {
        //     $loginController = new LoginController();
        //     $userRole = \Auth::user()->role;
        //     $roleDisplayName = $loginController->getRoleDisplayName($userRole);
        // }

        return view('layouts.pelaksana.dashboard', compact('dashboardStats', 'tugasDetails'));
    }
}