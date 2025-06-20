<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // Pastikan ini ada

    protected $fillable = ['nama', 'nim', 'jurusan', 'prodi'];

    // Relasi polimorfik ke DetailPelaksanaTugas
    public function detailPelaksanaTugas()
    {
        return $this->morphMany(DetailPelaksanaTugas::class, 'personable');
    }
}