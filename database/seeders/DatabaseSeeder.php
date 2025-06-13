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
        \App\Models\User::create([
    'name' => 'Fahrizal Mudzaqi M',
    'email' => 'fahrizal.mudzaqi.tif22@polban.ac.id',
    'password' => bcrypt('pengusul123'),
    'role' => 'pengusul',
    ]);

    \App\Models\User::create([
        'name' => 'Faras Rama Mahadika',
        'email' => 'faras@polban.ac.id',
        'password' => bcrypt('wadir1123'),
        'role' => 'wadir_1',
    ]);

    \App\Models\User::create([
        'name' => 'Angeline Putri',
        'email' => 'angeline@polban.ac.id',
        'password' => bcrypt('wadir123'),
        'role' => 'wadir',
    ]);

    \App\Models\User::create([
        'name' => 'Naufal Syafiq S',
        'email' => 'naufal.syafiq.tif22@polban.ac.id',
        'password' => bcrypt('direktur123'),
        'role' => 'direktur',
    ]);

    \App\Models\User::create([
        'name' => 'Muhammad Syaifullah',
        'email' => 'syaifullah@polban.ac.id',
        'password' => bcrypt('direktur123'),
        'role' => 'bku',
    ]);

    \App\Models\User::create([
        'name' => 'Adrian Eka',
        'email' => 'adrian@polban.ac.id',
        'password' => bcrypt('direktur123'),
        'role' => 'admin',
    ]);

    \App\Models\User::create([
        'name' => 'Ahmad Fauzy',
        'email' => 'fauzy@polban.ac.id',
        'password' => bcrypt('direktur123'),
        'role' => 'pelaksana',
    ]);
        $this->call([
            PegawaiSeeder::class,
            MahasiswaSeeder::class,
        ]);
    }
}
