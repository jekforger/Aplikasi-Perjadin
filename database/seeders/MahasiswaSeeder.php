<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i=1; $i <=1000 ; $i++) { 
            \DB::table('mahasiswa')->insert([
                'nama' => $faker -> name,
                'nim' => $faker -> numerify('221######'),
                'jurusan' => $faker -> randomElement([
                    'Teknik Informatika',
                    'Teknik Refrigasi dan Tata Udara',
                    'Teknik Mesin',
                    'Teknik Energi',
                    'Teknik Kimia',
                    'Teknik Sipil',
                    'Akuntansi',
                    'Administrasi Niaga',
                    'Bahasa Inggris',
                    'Teknik Elektro',
                ]),
                'prodi' => $faker -> randomElement([
                    'D3 Teknik Informatika',
                    'D4 Teknik Informatika',
                    'D3 Bahasa Inggris',
                    'D3 Akuntansi',
                    'D3 Teknik Elektro',
                    'D4 Teknik Elektro',
                    'D3 Teknik Sipil',
                    'D3 Teknik Mesin',
                    'D3 Teknik Kimia',
                    'D4 Administrasi Niaga',
                    'D3 Teknik Energi',
                    'D3 Teknik Refritasi dan Tata Udara',
                ]), 
            ]);
        }
    }
}
