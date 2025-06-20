<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'para_file_path', // <-- TAMBAHKAN INI
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi untuk pengusulan surat tugas oleh user ini
    public function suratTugasPengusulan()
    {
        return $this->hasMany(SuratTugas::class, 'user_id', 'id');
    }

    // Relasi untuk surat tugas yang disetujui/ditolak oleh Wadir ini
    public function suratTugasWadirApprovals()
    {
        return $this->hasMany(SuratTugas::class, 'wadir_approver_id', 'id');
    }

    // Relasi untuk surat tugas yang disetujui/ditolak oleh Direktur ini
    public function suratTugasDirekturApprovals()
    {
        return $this->hasMany(SuratTugas::class, 'direktur_approver_id', 'id');
    }
}