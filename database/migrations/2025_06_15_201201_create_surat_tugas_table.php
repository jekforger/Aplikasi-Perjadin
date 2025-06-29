// database/migrations/YYYY_MM_DD_HHMMSS_create_surat_tugas_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id('surat_tugas_id'); // Primary Key sesuai PDM

            // Kolom dari PDM dan Form Pengusulan
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pengusul
            $table->string('nomor_surat_usulan_jurusan'); // Dari form: nomor_surat_usulan
            $table->string('nomor_surat_tugas_resmi')->nullable(); // Nomor resmi setelah diterbitkan
            $table->text('perihal_tugas'); // Dari form: nama_kegiatan
            $table->string('kota_tujuan')->nullable(); // Bisa diisi dari alamat_kegiatan atau dikosongkan dulu
            $table->date('tanggal_berangkat'); // Hasil parsing dari tanggal_pelaksanaan
            $table->date('tanggal_kembali'); // Hasil parsing dari tanggal_pelaksanaan
            $table->string('status_surat')->default('draft'); // Status pengajuan: draft, diajukan_wadir, disetujui_wadir, dll.
            $table->text('catatan_revisi')->nullable(); // Catatan umum untuk revisi (Wadir/Direktur)
            $table->string('path_file_surat_usulan')->nullable(); // Dari form: surat_undangan_path
            $table->string('path_file_surat_tugas_final')->nullable(); // Path PDF surat tugas final
            $table->string('sumber_dana'); // Dari form: pembiayaan
            $table->boolean('pagu_desentralisasi')->default(false); // Dari form: pagu_desentralisasi_checkbox
            // $table->decimal('pagu_nominal', 15, 2)->nullable(); // Opsional, jika nanti ada input nominal

            // Timestamps persetujuan dari PDM
            $table->timestamp('tanggal_paraf_wadir')->nullable(); // Waktu Wadir menyetujui/memparaf
            $table->timestamp('tanggal_persetujuan_direktur')->nullable(); // Waktu Direktur menyetujui
            
            // Kolom untuk signature digital
            $table->text('wadir_signature_data')->nullable(); // Data signature base64 dari Wadir
            $table->text('direktur_signature_data')->nullable(); // Data signature base64 dari Direktur
            $table->json('wadir_signature_position')->nullable(); // Posisi signature Wadir (x, y, width, height)
            $table->json('direktur_signature_position')->nullable(); // Posisi signature Direktur (x, y, width, height)

            $table->boolean('is_surat_perintah_langsung')->default(false); // Dari PDM

            $table->timestamps(); // created_at (tanggal_pengusulan), updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_tugas');
    }
};