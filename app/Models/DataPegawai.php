<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPegawai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 
        'nip',
        'pangkat',
        'golongan',
        'jabatan'
    ];
}
