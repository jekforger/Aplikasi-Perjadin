<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' dengan tipe string, setelah kolom 'email'
            // default 'pengusul' adalah nilai awal jika tidak diisi
            // nullable() berarti kolom ini boleh kosong, tapi sebaiknya diisi
            $table->string('role')->default('pengusul')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom 'role' jika migrasi di-rollback
            $table->dropColumn('role');
        });
    }
};