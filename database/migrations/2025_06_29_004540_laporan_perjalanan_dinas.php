<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_perjalanan_dinas', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_pengumpulan_laporan');
            $table->enum('status_laporan', ['draft', 'diajukan', 'diverifikasi', 'ditolak']);
            $table->text('catatan_verifikasi_bku')->nullable();
            $table->date('tanggal_verifikasi_bku')->nullable();
            $table->unsignedBigInteger('verifikator_bku_user_id')->nullable();
            $table->timestamps();
        
            $table->foreign('verifikator_bku_user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_perjalanan_dinas');
    }
};
