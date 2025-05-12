<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i=1; $i <=500 ; $i++) { 
            \DB::table('pegawai')->insert([
                'nama' => $faker -> name,
                'nip' => '199109162019032026',
                'pangkat' => 'Penata Muda Tingkat I',
                'golongan' => 'III/b', 
                'jabatan' => 'Staf Humas',
            ]);
        }
    }
}
