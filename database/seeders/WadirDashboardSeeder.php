<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SuratTugas;
use App\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Models\DetailPelaksanaTugas;
use Carbon\Carbon;
use Faker\Factory as Faker;

class WadirDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $pengusul = User::where('role', 'pengusul')->first();
        $wadir1 = User::where('role', 'wadir_1')->first();
        $direktur = User::where('role', 'direktur')->first();
        $wadir2 = User::where('role', 'wadir_2')->first();

        if (!$pengusul || !$wadir1) {
            $this->command->error("Pengusul or Wadir I user not found. Please ensure DatabaseSeeder runs correctly and creates these users.");
            return;
        }

        $pegawaiSample = Pegawai::inRandomOrder()->take(10)->get();
        $mahasiswaSample = Mahasiswa::inRandomOrder()->take(10)->get();

        if ($pegawaiSample->isEmpty() && $mahasiswaSample->isEmpty()) {
            $this->command->warn("No personnel (Pegawai/Mahasiswa) found. Cannot create detailed Surat Tugas with personnel.");
        }


        // --- 1. Buat 5 Surat Tugas dengan status 'pending_wadir_review' ---
        for ($i = 0; $i < 5; $i++) {
            $st = SuratTugas::create([
                'user_id'                    => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-Pending-########'),
                'diusulkan_kepada'           => 'Wakil Direktur I',
                'perihal_tugas'              => $faker->sentence(mt_rand(4, 8)) . ' (Pending Review ' . ($i + 1) . ')', // Tambahkan nomor untuk identifikasi unik
                'tempat_kegiatan'            => $faker->company() . ', ' . $faker->city(),
                'alamat_kegiatan'            => $faker->address(),
                'kota_tujuan'                => $faker->city(),
                'tanggal_berangkat'          => Carbon::today()->addDays(mt_rand(5, 10)),
                'tanggal_kembali'            => Carbon::today()->addDays(mt_rand(11, 15)),
                'status_surat'               => 'pending_wadir_review',
                'sumber_dana'                => $faker->randomElement(['Polban', 'Penyelenggara']),
                'pagu_desentralisasi'        => $faker->boolean(),
                'ditugaskan_sebagai'         => $faker->jobTitle(),
            ]);

            // PENTING: Panggil fungsi helper yang akan menambahkan HANYA SATU personel
            $this->attachSingleRandomPersonel($st, $faker, $pegawaiSample, $mahasiswaSample);
        }
        $this->command->info("5 'pending_wadir_review' Surat Tugas created for Wakil Direktur I.");

        // --- 2. Buat 2 Surat Tugas dengan status 'approved_by_wadir' ---
        for ($i = 0; $i < 2; $i++) {
            $st = SuratTugas::create([
                'user_id'                    => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-Approved-########'),
                'diusulkan_kepada'           => 'Wakil Direktur I',
                'perihal_tugas'              => $faker->sentence(mt_rand(4, 8)) . ' (Approved by Wadir ' . ($i + 1) . ')',
                'tempat_kegiatan'            => $faker->company() . ', ' . $faker->city(),
                'alamat_kegiatan'            => $faker->address(),
                'kota_tujuan'                => $faker->city(),
                'tanggal_berangkat'          => Carbon::today()->addDays(mt_rand(16, 20)),
                'tanggal_kembali'            => Carbon::today()->addDays(mt_rand(21, 25)),
                'status_surat'               => 'approved_by_wadir',
                'tanggal_paraf_wadir'        => Carbon::now()->subDays(mt_rand(1, 3)),
                'wadir_approver_id'          => $wadir1->id,
                'sumber_dana'                => $faker->randomElement(['RM', 'PNBP']),
                'pagu_desentralisasi'        => $faker->boolean(),
                'ditugaskan_sebagai'         => $faker->jobTitle(),
            ]);
            $this->attachSingleRandomPersonel($st, $faker, $pegawaiSample, $mahasiswaSample);
        }
        $this->command->info("2 'approved_by_wadir' Surat Tugas created.");

        // --- 3. Buat 1 Surat Tugas dengan status 'diterbitkan' ---
        for ($i = 0; $i < 1; $i++) {
             $st = SuratTugas::create([
                'user_id'                    => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-Issued-########'),
                'nomor_surat_tugas_resmi'    => $faker->numerify('RESMI/ST/###/' . Carbon::now()->format('Y')),
                'diusulkan_kepada'           => 'Wakil Direktur I',
                'perihal_tugas'              => $faker->sentence(mt_rand(4, 8)) . ' (Diterbitkan)',
                'tempat_kegiatan'            => $faker->company() . ', ' . $faker->city(),
                'alamat_kegiatan'            => $faker->address(),
                'kota_tujuan'                => $faker->city(),
                'tanggal_berangkat'          => Carbon::today()->subDays(mt_rand(1, 3)),
                'tanggal_kembali'            => Carbon::today()->addDays(mt_rand(1, 3)),
                'status_surat'               => 'diterbitkan',
                'tanggal_paraf_wadir'        => Carbon::now()->subDays(5),
                'wadir_approver_id'          => $wadir1->id,
                'tanggal_persetujuan_direktur' => Carbon::now()->subDays(4),
                'direktur_approver_id'       => $direktur->id ?? null,
                'sumber_dana'                => $faker->randomElement(['RM', 'PNBP']),
                'pagu_desentralisasi'        => $faker->boolean(),
                'ditugaskan_sebagai'         => $faker->jobTitle(),
                'path_file_surat_tugas_final' => 'dummy_pdfs/surat_tugas_' . $faker->unique()->slug() . '.pdf',
            ]);
            $this->attachSingleRandomPersonel($st, $faker, $pegawaiSample, $mahasiswaSample);
        }
        $this->command->info("1 'diterbitkan' Surat Tugas created.");

        // --- 4. Buat 1 Surat Tugas dengan status 'rejected_by_wadir' ---
        for ($i = 0; $i < 1; $i++) {
            SuratTugas::create([
                'user_id'                    => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-Rejected-########'),
                'diusulkan_kepada'           => 'Wakil Direktur I',
                'perihal_tugas'              => $faker->sentence(mt_rand(4, 8)) . ' (Ditolak Wadir)',
                'tempat_kegiatan'            => $faker->company() . ', ' . $faker->city(),
                'alamat_kegiatan'            => $faker->address(),
                'kota_tujuan'                => $faker->city(),
                'tanggal_berangkat'          => Carbon::today()->subDays(mt_rand(10, 15)),
                'tanggal_kembali'            => Carbon::today()->subDays(mt_rand(8, 12)),
                'status_surat'               => 'rejected_by_wadir',
                'catatan_revisi'             => 'Tidak sesuai dengan prioritas kebutuhan unit.',
                'tanggal_paraf_wadir'        => Carbon::now()->subDays(7),
                'wadir_approver_id'          => $wadir1->id,
                'sumber_dana'                => $faker->randomElement(['Polban']),
                'pagu_desentralisasi'        => $faker->boolean(),
                'ditugaskan_sebagai'         => $faker->jobTitle(),
            ]);
        }
        $this->command->info("1 'rejected_by_wadir' Surat Tugas created.");

        // --- 5. Buat 1 Surat Tugas untuk Wakil Direktur II (untuk tes filtering) ---
        $wadir2 = User::where('role', 'wadir_2')->first();
        if ($wadir2) {
             SuratTugas::create([
                'user_id'                    => $pengusul->id,
                'nomor_surat_usulan_jurusan' => $faker->numerify('ST-Wadir2-########'),
                'diusulkan_kepada'           => 'Wakil Direktur II',
                'perihal_tugas'              => 'Rapat Koordinasi Bidang ' . $faker->word() . ' (Wadir II)',
                'tempat_kegiatan'            => $faker->company() . ', ' . $faker->city(),
                'alamat_kegiatan'            => $faker->address(),
                'kota_tujuan'                => $faker->city(),
                'tanggal_berangkat'          => Carbon::today()->addDays(2),
                'tanggal_kembali'            => Carbon::today()->addDays(3),
                'status_surat'               => 'pending_wadir_review',
                'sumber_dana'                => 'Polban',
                'pagu_desentralisasi'        => false,
                'ditugaskan_sebagai'         => 'Peserta',
            ]);
            $this->command->info("1 'pending_wadir_review' Surat Tugas created for Wakil Direktur II.");
        }

        $this->command->info("Seeding Direktur/Wadir Dashboard Data Completed.");
    }

    /**
     * Helper function to attach HANYA SATU personel to a SuratTugas.
     * Mengganti attachRandomPersonel sebelumnya.
     */
    private function attachSingleRandomPersonel($suratTugas, $faker, $pegawaiSample, $mahasiswaSample)
    {
        $personnelAdded = false;

        // Coba tambahkan pegawai dulu
        if (!$pegawaiSample->isEmpty() && $faker->boolean(70)) { // 70% chance to pick a Pegawai if available
            $person = $pegawaiSample->random();
            $suratTugas->detailPelaksanaTugas()->create([
                'personable_id' => $person->id,
                'personable_type' => Pegawai::class,
                'status_sebagai' => $faker->randomElement(['Ketua Tim', 'Anggota', 'Peserta']),
            ]);
            $personnelAdded = true;
        }

        // Jika pegawai tidak ditambahkan atau tidak ada, coba mahasiswa
        if (!$personnelAdded && !$mahasiswaSample->isEmpty()) {
            $person = $mahasiswaSample->random();
            $suratTugas->detailPelaksanaTugas()->create([
                'personable_id' => $person->id,
                'personable_type' => Mahasiswa::class,
                'status_sebagai' => $faker->randomElement(['Peserta', 'Pendamping']),
            ]);
            $personnelAdded = true;
        }

        if (!$personnelAdded) {
            $this->command->warn("No personnel attached for Surat Tugas ID {$suratTugas->surat_tugas_id}. Personnel samples might be empty.");
        }
    }
}