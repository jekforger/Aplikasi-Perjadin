<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pegawai;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Langkah 1: Buat pegawai kunci secara eksplisit DULUAN ---
        $pelaksanaPegawai = Pegawai::firstOrCreate(
            ['nip' => '199001012020121005'],
            [
                'nama' => 'Ahmad Fauzy', // <-- Benar
                'pangkat' => 'Penata Muda',
                'golongan' => 'III/a',
                'jabatan' => 'Staf Umum'
            ]
        );
        $pengusulPegawai = Pegawai::firstOrCreate(
            ['nip' => '199001012020121001'],
            ['nama' => 'Pengusul Utama', 'pangkat' => 'Penata', 'golongan' => 'III/c', 'jabatan' => 'Ketua Jurusan'] // <-- Benar
        );
        $wadir1Pegawai = Pegawai::firstOrCreate(
            ['nip' => '198501012015121001'],
            ['nama' => 'Faras Rama Mahadika', 'pangkat' => 'Pembina', 'golongan' => 'IV/a', 'jabatan' => 'Wakil Direktur I'] // <-- Benar
        );
        $direkturPegawai = Pegawai::firstOrCreate(
            ['nip' => '198001012010121001'],
            ['nama' => 'Naufal Syafiq S', 'pangkat' => 'Pembina Utama', 'golongan' => 'IV/e', 'jabatan' => 'Direktur'] // <-- PERBAIKAN DI SINI (name -> nama)
        );
        $wadir2Pegawai = Pegawai::firstOrCreate(
            ['nip' => '198601012016121001'],
            ['nama' => 'Budi Santoso', 'pangkat' => 'Pembina', 'golongan' => 'IV/a', 'jabatan' => 'Wakil Direktur II'] // <-- Benar
        );

        // --- Langkah 2: Seed sisa data master dari Faker ---
        $this->call([
            PegawaiSeeder::class,
        ]);

        // --- Langkah 3: Buat user berdasarkan pegawai yang sudah ada ---
        User::firstOrCreate(
            ['email' => 'pelaksana@polban.ac.id'],
            ['name' => $pelaksanaPegawai->nama, 'password' => bcrypt('password'), 'role' => 'pelaksana', 'pegawai_id' => $pelaksanaPegawai->id]
        );
        User::firstOrCreate(
            ['email' => 'pengusul@polban.ac.id'],
            ['name' => $pengusulPegawai->nama, 'password' => bcrypt('password'), 'role' => 'pengusul', 'pegawai_id' => $pengusulPegawai->id]
        );
        User::firstOrCreate(
            ['email' => 'wadir1@polban.ac.id'],
            ['name' => $wadir1Pegawai->nama, 'password' => bcrypt('password'), 'role' => 'wadir_1', 'pegawai_id' => $wadir1Pegawai->id]
        );
        User::firstOrCreate(
            ['email' => 'direktur@polban.ac.id'],
            ['name' => $direkturPegawai->nama, 'password' => bcrypt('password'), 'role' => 'direktur', 'pegawai_id' => $direkturPegawai->id]
        );
        User::firstOrCreate(
            ['email' => 'wadir2@polban.ac.id'],
            ['name' => $wadir2Pegawai->nama, 'password' => bcrypt('password'), 'role' => 'wadir_2', 'pegawai_id' => $wadir2Pegawai->id]
        );
        
        // Buat user yang tidak terhubung ke pegawai
        User::firstOrCreate(
            ['email' => 'admin@polban.ac.id'],
            ['name' => 'Admin Sistem', 'password' => bcrypt('password'), 'role' => 'admin']
        );
        User::firstOrCreate(
            ['email' => 'bku@polban.ac.id'],
            ['name' => 'Staf BKU', 'password' => bcrypt('password'), 'role' => 'bku']
        );

$this->call([
            PegawaiSeeder::class,
            MahasiswaSeeder::class,
            WadirDashboardSeeder::class, // Ini yang akan mengisi data Surat Tugas dan bergantung pada user, pegawai, mahasiswa
        ]);
    }
}