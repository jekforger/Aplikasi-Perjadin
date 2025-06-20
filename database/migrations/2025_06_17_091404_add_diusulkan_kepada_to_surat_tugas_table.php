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
            // Tambahkan kolom 'diusulkan_kepada' setelah 'user_id' atau 'nomor_surat_usulan_jurusan'
            // Lokasi penambahan ini tidak terlalu krusial, tapi 'after' membuatnya lebih rapi
            $table->string('diusulkan_kepada')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('diusulkan_kepada');
        });
    }
};