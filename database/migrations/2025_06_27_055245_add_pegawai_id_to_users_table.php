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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'pegawai_id' setelah kolom 'role'
            // Ini akan menjadi foreign key ke tabel 'pegawai'
            $table->foreignId('pegawai_id')
                  ->nullable() // Bisa null, misal untuk admin sistem yang bukan pegawai
                  ->after('role')
                  ->constrained('pegawai') // Menunjuk ke tabel 'pegawai'
                  ->onDelete('set null'); // Jika record pegawai dihapus, user ini tidak ikut terhapus, hanya pegawai_id-nya jadi null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['pegawai_id']);
            $table->dropColumn('pegawai_id');
        });
    }
};