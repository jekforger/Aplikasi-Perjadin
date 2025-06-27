<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Pegawai;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Seeder ini sekarang HANYA membuat 499 pegawai acak.
        // Pegawai kunci (seperti Ahmad Fauzy) akan dibuat di DatabaseSeeder.
        for ($i = 1; $i <= 499; $i++) {
            Pegawai::create([
                'nama' => $faker->unique()->name,
                'nip' => $faker->unique()->numerify('199###############'), // unique() penting!
                'pangkat' => 'Penata Muda Tingkat I',
                'golongan' => 'III/b',
                'jabatan' => 'Staf Humas',
            ]);
        }
    }
}