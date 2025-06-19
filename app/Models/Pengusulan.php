<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengusulan extends Model
{
    use HasFactory;

    protected $table = 'pengusulan'; // Pastikan nama tabel benar

    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'tempat_kegiatan',
        'diusulkan_kepada',
        'surat_undangan_path',
        'ditugaskan_sebagai',
        'tanggal_pelaksanaan',
        'alamat_kegiatan',
        'pembiayaan',
        'pagu_desentralisasi',
        'pagu_nominal',
        'provinsi',
        'nomor_surat_usulan',
        'personel_terpilih', // Ini akan otomatis di-cast ke array/objek jika Anda menambahkannya ke $casts
        'status',
    ];

    // Tambahkan ini jika Anda ingin 'personel_terpilih' di-cast otomatis
    protected $casts = [
        'personel_terpilih' => 'array',
        'pagu_desentralisasi' => 'boolean',
    ];

    // Relasi dengan User (jika pengusulan terkait dengan user yang mengajukan)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}