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

        // --- Dapatkan User yang Dibutuhkan untuk Relasi ---
        $pengusul = User::where('role', 'pengusul')->first();
        $wadir1 = User::where('role', 'wadir_1')->first();
        $direktur = User::where('role', 'direktur')->first();
        $wadir2 = User::where('role', 'wadir_2')->first();

        if (!$pengusul || !$wadir1) {
            $this->command->error("Pengusul or Wadir I user not found. Please ensure DatabaseSeeder runs correctly.");
            return;
        }

        // --- Dapatkan Sampel Pegawai dan Mahasiswa ---
        $pegawaiSample = Pegawai::where('nama', '!=', 'Ahmad Fauzy')->inRandomOrder()->take(10)->get();
        $mahasiswaSample = Mahasiswa::inRandomOrder()->take(10)->get();

        if ($pegawaiSample->isEmpty()) {
            $this->command->warn("No other pegawai found to be assigned randomly.");
        }


        // --- 1. Buat Surat Tugas dengan status 'pending_wadir_review' ---
        for ($i = 0; $i < 5; $i++) {
            $st = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => 'ST-PENDING-' . $faker->unique()->numerify('#####'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => 'Review Dokumen Proyek ' . $faker->company,
                'tempat_kegiatan' => 'Kantor Pusat ' . $faker->city,
                'alamat_kegiatan' => $faker->address,
                'kota_tujuan' => $faker->city,
                'tanggal_berangkat' => Carbon::today()->addDays(mt_rand(5, 10)),
                'tanggal_kembali' => Carbon::today()->addDays(mt_rand(11, 15)),
                'status_surat' => 'pending_wadir_review',
                'sumber_dana' => $faker->randomElement(['Polban', 'Penyelenggara']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);
            $this->attachSingleRandomPersonel($st, $faker, $pegawaiSample, $mahasiswaSample);
        }
        $this->command->info("5 'pending_wadir_review' Surat Tugas created for Wakil Direktur I.");

        // --- 2. Buat 2 Surat Tugas dengan status 'approved_by_wadir' ---
        for ($i = 0; $i < 2; $i++) {
            $st = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => 'ST-APPROVED-' . $faker->unique()->numerify('#####'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => 'Studi Banding Kurikulum ' . $faker->word,
                'tempat_kegiatan' => 'Universitas ' . $faker->city,
                'alamat_kegiatan' => $faker->address,
                'kota_tujuan' => $faker->city,
                'tanggal_berangkat' => Carbon::today()->addDays(mt_rand(16, 20)),
                'tanggal_kembali' => Carbon::today()->addDays(mt_rand(21, 25)),
                'status_surat' => 'approved_by_wadir',
                'tanggal_paraf_wadir' => Carbon::now()->subDays(mt_rand(1, 3)),
                'wadir_approver_id' => $wadir1->id,
                'sumber_dana' => $faker->randomElement(['RM', 'PNBP']),
                'pagu_desentralisasi' => $faker->boolean(),
                'ditugaskan_sebagai' => $faker->jobTitle(),
            ]);
            $this->attachSingleRandomPersonel($st, $faker, $pegawaiSample, $mahasiswaSample);
        }
        $this->command->info("2 'approved_by_wadir' Surat Tugas created.");

        // --- 3. Buat 1 Surat Tugas dengan status 'diterbitkan' dan tugaskan 'Ahmad Fauzy' ---
        $pelaksanaAhmadFauzy = Pegawai::where('nama', 'Ahmad Fauzy')->first();
        if ($pelaksanaAhmadFauzy) {
             $stDiterbitkan = SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => 'ST-ISSUED-PELAKSANA',
                'nomor_surat_tugas_resmi' => 'RESMI/ST/PELAKSANA/' . Carbon::now()->format('Y'),
                'diusulkan_kepada' => 'Wakil Direktur I',
                'perihal_tugas' => 'Pelatihan Sistem untuk Pelaksana',
                'tempat_kegiatan' => 'Auditorium Polban',
                'alamat_kegiatan' => 'Jl. Gegerkalong Hilir, Bandung',
                'kota_tujuan' => 'Bandung',
                'tanggal_berangkat' => Carbon::today()->subDays(1),
                'tanggal_kembali' => Carbon::today()->addDays(2),
                'status_surat' => 'diterbitkan',
                'tanggal_paraf_wadir' => Carbon::now()->subDays(5),
                'wadir_approver_id' => $wadir1->id,
                'tanggal_persetujuan_direktur' => Carbon::now()->subDays(4),
                'direktur_approver_id' => $direktur->id ?? null,
                'sumber_dana' => 'RM',
                'pagu_desentralisasi' => false,
                'ditugaskan_sebagai' => 'Peserta Pelatihan',
            ]);
            // Menugaskan Ahmad Fauzy secara eksplisit
            $stDiterbitkan->detailPelaksanaTugas()->create([
                'personable_id' => $pelaksanaAhmadFauzy->id,
                'personable_type' => Pegawai::class,
                'status_sebagai' => 'Peserta Pelatihan',
            ]);
            $this->command->info("1 'diterbitkan' Surat Tugas created and assigned to Ahmad Fauzy.");
        } else {
            $this->command->warn("Pegawai 'Ahmad Fauzy' not found, skipping his assignment.");
        }

        // --- 4. Buat 1 Surat Tugas dengan status 'rejected_by_wadir' ---
        SuratTugas::create([
            'user_id' => $pengusul->id,
            'nomor_surat_usulan_jurusan' => 'ST-REJECTED-' . $faker->unique()->numerify('#####'),
            'diusulkan_kepada' => 'Wakil Direktur I',
            'perihal_tugas' => 'Proposal Kegiatan Lanjutan',
            'tempat_kegiatan' => 'Kantor Cabang',
            'alamat_kegiatan' => $faker->address,
            'kota_tujuan' => $faker->city,
            'tanggal_berangkat' => Carbon::today()->subDays(mt_rand(10, 15)),
            'tanggal_kembali' => Carbon::today()->subDays(mt_rand(8, 12)),
            'status_surat' => 'rejected_by_wadir',
            'catatan_revisi' => 'Tidak sesuai dengan prioritas kebutuhan unit.',
            'tanggal_paraf_wadir' => Carbon::now()->subDays(7),
            'wadir_approver_id' => $wadir1->id,
            'sumber_dana' => 'Polban',
            'pagu_desentralisasi' => $faker->boolean(),
            'ditugaskan_sebagai' => $faker->jobTitle(),
        ]);
        $this->command->info("1 'rejected_by_wadir' Surat Tugas created.");

        // --- 5. Buat 1 Surat Tugas untuk Wakil Direktur II (untuk tes filtering) ---
        if ($wadir2) {
             SuratTugas::create([
                'user_id' => $pengusul->id,
                'nomor_surat_usulan_jurusan' => 'ST-WADIR2-' . $faker->unique()->numerify('#####'),
                'diusulkan_kepada' => 'Wakil Direktur II',
                'perihal_tugas' => 'Rapat Koordinasi Bidang ' . $faker->word,
                'tempat_kegiatan' => 'Kantor Regional',
                'alamat_kegiatan' => $faker->address,
                'kota_tujuan' => $faker->city,
                'tanggal_berangkat' => Carbon::today()->addDays(2),
                'tanggal_kembali' => Carbon::today()->addDays(3),
                'status_surat' => 'pending_wadir_review',
                'sumber_dana' => 'Polban',
                'pagu_desentralisasi' => false,
                'ditugaskan_sebagai' => 'Peserta',
            ]);
            $this->command->info("1 'pending_wadir_review' Surat Tugas created for Wakil Direktur II.");
        }

        $this->command->info("Seeding Direktur/Wadir Dashboard Data Completed.");
    }

    private function attachSingleRandomPersonel($suratTugas, $faker, $pegawaiSample, $mahasiswaSample)
    {
        $personnelAdded = false;
        if ($faker->boolean(70) && !$pegawaiSample->isEmpty()) {
            $person = $pegawaiSample->random();
            $suratTugas->detailPelaksanaTugas()->create([
                'personable_id' => $person->id,
                'personable_type' => Pegawai::class,
                'status_sebagai' => $faker->randomElement(['Ketua Tim', 'Anggota', 'Peserta']),
            ]);
            $personnelAdded = true;
        } elseif (!$mahasiswaSample->isEmpty()) {
            $person = $mahasiswaSample->random();
            $suratTugas->detailPelaksanaTugas()->create([
                'personable_id' => $person->id,
                'personable_type' => Mahasiswa::class,
                'status_sebagai' => $faker->randomElement(['Peserta', 'Pendamping']),
            ]);
            $personnelAdded = true;
        }
    }
}