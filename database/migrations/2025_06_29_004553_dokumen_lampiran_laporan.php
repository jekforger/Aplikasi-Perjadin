<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_lampiran_laporan', function (Blueprint $table) {
            $table->id('dokumen_lampiran_id');
            $table->foreignId('laporan_id')->constrained('laporan_perjalanan_dinas')->onDelete('cascade');
            $table->string('jenis_dokumen'); // misal: 'Bukti', 'Laporan'
            $table->string('nama_file');
            $table->string('path_file');
            $table->timestamp('tanggal_unggah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_lampiran_laporan');
    }
};
