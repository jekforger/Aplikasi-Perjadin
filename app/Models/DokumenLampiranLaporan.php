<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLampiranLaporan extends Model
{
    use HasFactory;

    protected $table = 'dokumen_lampiran_laporan';
    protected $primaryKey = 'dokumen_lampiran_id';

    protected $fillable = [
        'laporan_id',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'tanggal_unggah',
    ];

    /**
     * Relasi: Dokumen Lampiran milik satu Laporan Perjalanan Dinas
     */
    public function laporanPerjalananDinas()
    {
        return $this->belongsTo(LaporanPerjalananDinas::class, 'laporan_id', 'laporan_id');
    }
    
    public function dokumenLampiran()
    {
        return $this->hasMany(DokumenLampiranLaporan::class, 'laporan_id', 'laporan_id');
    }

}
