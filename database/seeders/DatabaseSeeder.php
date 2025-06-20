<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan User Model diimport

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- User Roles for Testing ---
        // Menggunakan email dan password yang sederhana untuk kemudahan pengujian.
        // Anda bisa mengubahnya sesuai kebutuhan.
        User::create([
            'name' => 'Fahrizal Mudzaqi M',
            'email' => 'pengusul@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'pengusul',
        ]);

        User::create([
            'name' => 'Faras Rama Mahadika',
            'email' => 'wadir1@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'wadir_1',
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'wadir2@polban.ac.id',
            'password' => bcrypt('password'),
            'role' => 'wadir_2',
        ]);

        User::create([
            'name' => 'Citra Dewi',
            'email' => 'wadir3@polban.ac.id',
            'password' => bcrypt('password'),
            'role' => 'wadir_3',
        ]);

        User::create([
            'name' => 'Dewi Anggraini',
            'email' => 'wadir4@polban.ac.id',
            'password' => bcrypt('password'),
            'role' => 'wadir_4',
        ]);

        User::create([
            'name' => 'Naufal Syafiq S',
            'email' => 'direktur@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'direktur',
        ]);

        User::create([
            'name' => 'Muhammad Syaifullah',
            'email' => 'bku@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'bku',
        ]);

        User::create([
            'name' => 'Adrian Eka',
            'email' => 'admin@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Ahmad Fauzy',
            'email' => 'pelaksana@polban.ac.id',
            'password' => bcrypt('password'), // Password: password
            'role' => 'pelaksana',
        ]);

        // --- Panggil seeder-seeder lainnya ---
        // Penting: Pastikan urutannya benar (user, pegawai, mahasiswa sebelum WadirDashboardSeeder)
        $this->call([
            PegawaiSeeder::class,
            MahasiswaSeeder::class,
            WadirDashboardSeeder::class, // Ini yang akan mengisi data Surat Tugas dan bergantung pada user, pegawai, mahasiswa
        ]);
    }
}