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
        Schema::table('surat_tugas', function (Blueprint $table) {
            // Tambahkan kolom 'tempat_kegiatan' setelah 'perihal_tugas'
            $table->text('tempat_kegiatan')->after('perihal_tugas');

            // Tambahkan kolom 'alamat_kegiatan' setelah 'tempat_kegiatan'
            $table->text('alamat_kegiatan')->after('tempat_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('tempat_kegiatan');
            $table->dropColumn('alamat_kegiatan');
        });
    }
};