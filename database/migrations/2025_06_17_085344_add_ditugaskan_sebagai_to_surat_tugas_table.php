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
            // Tambahkan kolom 'ditugaskan_sebagai' setelah 'perihal_tugas'
            $table->string('ditugaskan_sebagai')->after('perihal_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('ditugaskan_sebagai');
        });
    }
};