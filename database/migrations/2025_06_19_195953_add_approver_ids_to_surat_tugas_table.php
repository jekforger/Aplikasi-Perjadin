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
            // Tambahkan kolom wadir_approver_id (foreign key ke users.id) setelah tanggal_paraf_wadir
            $table->foreignId('wadir_approver_id')
                  ->nullable() // Bisa null jika belum disetujui Wadir
                  ->constrained('users')
                  ->onDelete('set null') // Jika user Wadir dihapus, set null di sini
                  ->after('tanggal_paraf_wadir');

            // Tambahkan kolom direktur_approver_id (foreign key ke users.id) setelah tanggal_persetujuan_direktur
            $table->foreignId('direktur_approver_id')
                  ->nullable() // Bisa null jika belum disetujui Direktur
                  ->constrained('users')
                  ->onDelete('set null') // Jika user Direktur dihapus, set null di sini
                  ->after('tanggal_persetujuan_direktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_tugas', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu sebelum menghapus kolom
            $table->dropConstrainedForeignId('wadir_approver_id');
            $table->dropConstrainedForeignId('direktur_approver_id');
        });
    }
};