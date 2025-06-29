<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPelaksanaTugas extends Model
{
    use HasFactory;

    protected $table = 'detail_pelaksana_tugas';
    protected $primaryKey = 'detail_pelaksana_id';

    protected $fillable = [
        'surat_tugas_id',
        'personable_id',
        'personable_type',
        'status_sebagai',
    ];

    // Relasi polimorfik untuk mendapatkan detail pegawai atau mahasiswa
    public function personable()
    {
        return $this->morphTo();
    }

    // Relasi ke SuratTugas
    public function suratTugas()
    {
        return $this->belongsTo(SuratTugas::class, 'surat_tugas_id', 'surat_tugas_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'personable_id');
    }
}