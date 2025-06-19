<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Pengusul
        \App\Models\User::create([
            'name' => 'Fahrizal Mudzaqi M',
            'email' => 'fahrizal.mudzaqi.tif22@polban.ac.id',
            'password' => bcrypt('pengusul123'),
            'role' => 'pengusul',
        ]);

        // Wakil Direktur I
        \App\Models\User::create([
            'name' => 'Faras Rama Mahadika',
            'email' => 'faras.wadir1@polban.ac.id', // Ganti email agar unik jika sudah ada
            'password' => bcrypt('wadir1123'),
            'role' => 'wadir_1',
        ]);

        // Wakil Direktur II (Contoh, tambahkan user lainnya)
        \App\Models\User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.wadir2@polban.ac.id',
            'password' => bcrypt('wadir2123'),
            'role' => 'wadir_2',
        ]);

        // Wakil Direktur III
        \App\Models\User::create([
            'name' => 'Citra Dewi',
            'email' => 'citra.wadir3@polban.ac.id',
            'password' => bcrypt('wadir3123'),
            'role' => 'wadir_3',
        ]);

        // Wakil Direktur IV
        \App\Models\User::create([
            'name' => 'Dewi Anggraini',
            'email' => 'dewi.wadir4@polban.ac.id',
            'password' => bcrypt('wadir4123'),
            'role' => 'wadir_4',
        ]);

        // Hapus user 'Angeline Putri' dengan role 'wadir' jika Anda ingin spesifik
        // atau jika Anda ingin 'wadir' ini bisa melihat semua, Anda perlu logika khusus

        // Direktur
        \App\Models\User::create([
            'name' => 'Naufal Syafiq S',
            'email' => 'naufal.direktur@polban.ac.id',
            'password' => bcrypt('direktur123'),
            'role' => 'direktur',
        ]);

        // BKU
        \App\Models\User::create([
            'name' => 'Muhammad Syaifullah',
            'email' => 'syaifullah.bku@polban.ac.id',
            'password' => bcrypt('bku123'),
            'role' => 'bku',
        ]);

        // Admin
        \App\Models\User::create([
            'name' => 'Adrian Eka',
            'email' => 'adrian.admin@polban.ac.id',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Pelaksana
        \App\Models\User::create([
            'name' => 'Ahmad Fauzy',
            'email' => 'fauzy.pelaksana@polban.ac.id',
            'password' => bcrypt('pelaksana123'),
            'role' => 'pelaksana',
        ]);

        // Panggil seeder lainnya
        $this->call([
            PegawaiSeeder::class,
            MahasiswaSeeder::class,
            WadirDashboardSeeder::class,
        ]);
    }
}