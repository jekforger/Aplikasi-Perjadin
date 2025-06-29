<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratTugas extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas';
    protected $primaryKey = 'surat_tugas_id';

    protected $fillable = [
        'user_id',
        'nomor_surat_usulan_jurusan',
        'diusulkan_kepada',
        'nomor_surat_tugas_resmi',
        'perihal_tugas',
        'tempat_kegiatan', // <-- TAMBAHKAN INI
        'alamat_kegiatan', // <-- TAMBAHKAN INI
        'kota_tujuan',
        'tanggal_berangkat',
        'tanggal_kembali',
        'status_surat',
        'catatan_revisi',
        'path_file_surat_usulan',
        'path_file_surat_tugas_final',
        'sumber_dana',
        'pagu_desentralisasi',
        'tanggal_paraf_wadir',
        'tanggal_persetujuan_direktur',
        'is_surat_perintah_langsung',
        'ditugaskan_sebagai',
        'wadir_approver_id',
        'direktur_approver_id',
    ];

    protected $casts = [
        'tanggal_berangkat' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_paraf_wadir' => 'datetime',
        'tanggal_persetujuan_direktur' => 'datetime',
        'pagu_desentralisasi' => 'boolean',
        'is_surat_perintah_langsung' => 'boolean',
    ];

    // Relasi ke User (Pengusul)
    public function pengusul()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke DetailPelaksanaTugas (polymorphic relationship)
    public function detailPelaksanaTugas()
    {
        return $this->hasMany(DetailPelaksanaTugas::class, 'surat_tugas_id', 'surat_tugas_id');
    }

    public function laporanPerjalananDinas()
    {
        return $this->hasOne(LaporanPerjalananDinas::class, 'surat_tugas_id', 'surat_tugas_id');
    }

    public function dokumenLampiran()
    {
        return $this->hasMany(DokumenLampiranLaporan::class, 'laporan_id', 'laporan_id');
    }

    public function laporan()
    {
        return $this->hasOne(LaporanPerjalananDinas::class, 'surat_tugas_id');
    }

    // Relasi untuk siapa Wadir yang menyetujui (jika Anda menambahkan kolom wadir_approver_id di SuratTugas)
    public function wadirApprover()
    {
        return $this->belongsTo(User::class, 'wadir_approver_id', 'id');
    }

    // Relasi untuk siapa Direktur yang menyetujui (jika Anda menambahkan kolom direktur_approver_id di SuratTugas)
    public function direkturApprover()
    {
        return $this->belongsTo(User::class, 'direktur_approver_id', 'id');
    }
}