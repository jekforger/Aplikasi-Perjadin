<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPerjalananDinas extends Model
{
    use HasFactory;

    protected $table = 'laporan_perjalanan_dinas';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'surat_tugas_id',
        'user_id',
        'tanggal_pengumpulan_laporan',
        'status_laporan',
        'catatan_verifikasi_bku',
        'tanggal_verifikasi_bku',
        'verifikator_bku_user_id',
    ];

    // Relasi: 1 laporan punya banyak dokumen lampiran
    public function dokumenLampiran()
    {
        return $this->hasMany(DokumenLampiranLaporan::class, 'laporan_id', 'laporan_id');
    }

    // Relasi: laporan milik 1 surat tugas
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id', 'id');
    }

    // Relasi: laporan milik 1 user (pengusul)
    public function pengusul()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi: laporan diverifikasi oleh 1 user (verifikator BKU)
    public function bkuApprover()
    {
        return $this->belongsTo(User::class, 'verifikator_bku_user_id', 'id');
    }
    
}
