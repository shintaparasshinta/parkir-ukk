<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'petugas_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }

    // Cek role pakai kolom 'role' di tabel users (untuk sidebar & tampilan)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPetugas()
    {
        return $this->role === 'petugas';
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    // Cek permission pakai Spatie (untuk on/off fitur dari Manajemen Role)
    public function hasFeature($permission)
    {
        return $this->hasPermissionTo($permission);
    }
}