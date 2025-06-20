// database/migrations/YYYY_MM_DD_HHMMSS_create_detail_pelaksana_tugas_table.php

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
        Schema::create('detail_pelaksana_tugas', function (Blueprint $table) {
            $table->id('detail_pelaksana_id'); // Primary Key sesuai PDM
            $table->foreignId('surat_tugas_id')->constrained('surat_tugas', 'surat_tugas_id')->onDelete('cascade');

            // Kolom polimorfik untuk menunjuk ke pegawai atau mahasiswa
            $table->morphs('personable'); // Ini akan membuat kolom 'personable_id' (unsignedBigInteger) dan 'personable_type' (string)

            $table->string('status_sebagai'); // Dari form: ditugaskan_sebagai (akan diulang untuk setiap personel)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pelaksana_tugas');
    }
};