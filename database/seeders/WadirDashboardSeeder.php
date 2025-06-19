<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratTugas;
use App\Models\Pegawai;
use App\Models\Mahasiswa;
use Carbon\Carbon;
use Faker\Factory as Faker;

class WadirDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $pengusul = User::where('role', 'pengusul')->first();
        $wadir1 = User::where('role', 'wadir_1')->first();
        $direktur = User::where('role', 'direktur')->first();

        if (!$pengusul || !$wadir1) {
            $this->command->error("Pengusul or Wadir I user not found. Please run DatabaseSeeder first.");
            return;
        }

        $pegawaiSample = Pegawai::inRandomOrder()->take(5)->get();
        $mahasiswaSample = Mahasiswa::inRandomOrder()->take(5)->get();

        if ($pegawaiSample->isEmpty() && $mahasiswaSample->isEmpty()) {
            $this->command->warn("No personnel (Pegawai/Mahasiswa) found. Cannot create detailed Surat Tugas.");
            // Lanjutkan eksekusi seeder, tapi tanpa detail personel
        }


        // --- Buat 5 Surat Tugas dengan status pending_wadir_review ---
        for ($i = 1; $i <= 5; $i++) {
            $st = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-########'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => $faker->sentence(5) . ' ' . $faker->word() . ' ' . $faker->year(),
                'tempat_kegiatan' => $faker->company() . ', ' . $faker->city(), // <-- TAMBAHKAN INI
                'alamat_kegiatan' => $faker->address(), // <-- TAMBAHKAN INI
                'kota_tujuan' => $faker->city(),
                'tanggal_berangkat' => Carbon::today()->addDays($faker->numberBetween(5, 10)),
                'tanggal_kembali' => Carbon::today()->addDays($faker->numberBetween(11, 15)),
                'status_surat' => 'pending_wadir_review',
                'sumber_dana' => $faker->randomElement(['Polban', 'Penyelenggara']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);

            // ... (logika penambahan personel tetap sama) ...
            $personelCount = $faker->numberBetween(1, 2);
            for ($p = 0; $p < $personelCount; $p++) {
                if ($faker->boolean(70) && !$pegawaiSample->isEmpty()) {
                    $person = $pegawaiSample->random();
                    $st->detailPelaksanaTugas()->create([
                        'personable_id' => $person->id,
                        'personable_type' => Pegawai::class,
                        'status_sebagai' => $faker->randomElement(['Ketua Tim', 'Anggota', 'Peserta']),
                    ]);
                } elseif (!$mahasiswaSample->isEmpty()) {
                    $person = $mahasiswaSample->random();
                    $st->detailPelaksanaTugas()->create([
                        'personable_id' => $person->id,
                        'personable_type' => Mahasiswa::class,
                        'status_sebagai' => $faker->randomElement(['Peserta', 'Pendamping']),
                    ]);
                }
            }
        }
        $this->command->info("5 'pending_wadir_review' Surat Tugas created for Wakil Direktur I.");

        // --- Buat 2 Surat Tugas dengan status approved_by_wadir ---
        for ($i = 1; $i <= 2; $i++) {
            $st = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-########'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => $faker->sentence(5) . ' ' . $faker->word() . ' ' . $faker->year(),
                'tempat_kegiatan' => $faker->company() . ', ' . $faker->city(), // <-- TAMBAHKAN INI
                'alamat_kegiatan' => $faker->address(), // <-- TAMBAHKAN INI
                'kota_tujuan' => $faker->city(),
                'tanggal_berangkat' => Carbon::today()->addDays($faker->numberBetween(16, 20)),
                'tanggal_kembali' => Carbon::today()->addDays($faker->numberBetween(21, 25)),
                'status_surat' => 'approved_by_wadir',
                'tanggal_paraf_wadir' => Carbon::now()->subDays($faker->numberBetween(1, 3)),
                'wadir_approver_id' => $wadir1->id,
                'sumber_dana' => $faker->randomElement(['Polban', 'Penyelenggara']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);
            // ... (logika penambahan personel tetap sama) ...
            if (!$pegawaiSample->isEmpty()) {
                $st->detailPelaksanaTugas()->create([
                    'personable_id' => $pegawaiSample->random()->id,
                    'personable_type' => Pegawai::class,
                    'status_sebagai' => $faker->randomElement(['Ketua', 'Anggota']),
                ]);
            }
        }
        $this->command->info("2 'approved_by_wadir' Surat Tugas created for Wakil Direktur I.");

        // --- Buat 1 Surat Tugas dengan status diterbitkan ---
        for ($i = 1; $i <= 1; $i++) {
             $st = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-########'),
                'nomor_surat_tugas_resmi' => $faker->numerify('RESMI/ST/###'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => $faker->sentence(5) . ' ' . $faker->word() . ' ' . $faker->year(),
                'tempat_kegiatan' => $faker->company() . ', ' . $faker->city(), // <-- TAMBAHKAN INI
                'alamat_kegiatan' => $faker->address(), // <-- TAMBAHKAN INI
                'kota_tujuan' => $faker->city(),
                'tanggal_berangkat' => Carbon::today()->subDays($faker->numberBetween(1, 3)),
                'tanggal_kembali' => Carbon::today()->addDays($faker->numberBetween(1, 3)),
                'status_surat' => 'diterbitkan',
                'tanggal_paraf_wadir' => Carbon::now()->subDays(5),
                'wadir_approver_id' => $wadir1->id,
                'tanggal_persetujuan_direktur' => Carbon::now()->subDays(4),
                'direktur_approver_id' => $direktur->id ?? null,
                'sumber_dana' => $faker->randomElement(['Polban', 'Penyelenggara']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);
            // ... (logika penambahan personel tetap sama) ...
            if (!$pegawaiSample->isEmpty()) {
                $st->detailPelaksanaTugas()->create([
                    'personable_id' => $pegawaiSample->random()->id,
                    'personable_type' => Pegawai::class,
                    'status_sebagai' => $faker->randomElement(['Koordinator', 'Anggota']),
                ]);
            }
        }
        $this->command->info("1 'diterbitkan' Surat Tugas created for Wakil Direktur I.");


        // --- Buat 1 Surat Tugas dengan status rejected_by_wadir ---
        for ($i = 1; $i <= 1; $i++) {
            SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-########'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => $faker->sentence(5) . ' ' . $faker->word() . ' ' . $faker->year(),
                'tempat_kegiatan' => $faker->company() . ', ' . $faker->city(), // <-- TAMBAHKAN INI
                'alamat_kegiatan' => $faker->address(), // <-- TAMBAHKAN INI
                'kota_tujuan' => $faker->city(),
                'tanggal_berangkat' => Carbon::today()->subDays($faker->numberBetween(10, 15)),
                'tanggal_kembali' => Carbon::today()->subDays($faker->numberBetween(8, 12)),
                'status_surat' => 'rejected_by_wadir',
                'catatan_revisi' => 'Tidak sesuai prioritas tahunan.',
                'tanggal_paraf_wadir' => Carbon::now()->subDays(7),
                'wadir_approver_id' => $wadir1->id,
                'sumber_dana' => $faker->randomElement(['Polban']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);
        }
        $this->command->info("1 'rejected_by_wadir' Surat Tugas created for Wakil Direktur I.");

        // --- Buat beberapa Surat Tugas untuk Wakil Direktur II (untuk tes filtering) ---
        $wadir2 = User::where('role', 'wadir_2')->first();
        if ($wadir2) {
             SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-########'),
                'diusulkan_kepada' => 'Wakil Direktur II',
                'perihal_tugas' => 'Rapat Koordinasi Bidang ' . $faker->word(),
                'tempat_kegiatan' => $faker->company() . ', ' . $faker->city(), // <-- TAMBAHKAN INI
                'alamat_kegiatan' => $faker->address(), // <-- TAMBAHKAN INI
                'kota_tujuan' => $faker->city(),
                'tanggal_berangkat' => Carbon::today()->addDays(2),
                'tanggal_kembali' => Carbon::today()->addDays(3),
                'status_surat' => 'pending_wadir_review',
                'sumber_dana' => 'Polban',
                'pagu_desentralisasi' => false,
                'ditugaskan_sebagai' => 'Peserta',
            ]);
            $this->command->info("1 'pending_wadir_review' Surat Tugas created for Wakil Direktur II.");
        }
    }
}