<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Foundation\Auth\User as Authenticatable; // Mengimpor kelas Authenticatable (bisa login)
use Illuminate\Notifications\Notifiable; // Mengimpor trait Notifiable (untuk notifikasi Laravel)
use Spatie\Permission\Traits\HasRoles; // Mengimpor trait HasRoles dari Spatie Permission

class User extends Authenticatable // Mendefinisikan model User yang bisa diautentikasi
{
    use HasFactory, Notifiable, HasRoles; // Mengaktifkan factory, notifikasi, dan role/permission Spatie

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'nama', // Nama lengkap pengguna
        'username', // Username untuk login (unik)
        'password', // Password (di-hash)
        'role', // Role dasar (admin/petugas/owner)
        'foto', // Path foto profil
    ];

    protected $hidden = [ // Kolom yang disembunyikan saat serialisasi
        'password', // Jangan tampilkan password di JSON
        'remember_token', // Jangan tampilkan remember token
    ];

    protected function casts(): array // Method cast atribut (gaya baru Laravel 10+)
    {
        return [
            'password' => 'hashed', // Otomatis hash password saat di-set
        ];
    }

    public function transaksi() // Relasi ke model Transaksi
    {
        return $this->hasMany(Transaksi::class, 'petugas_id'); // Satu petugas menangani banyak transaksi
    }

    public function logAktivitas() // Relasi ke model LogAktivitas
    {
        return $this->hasMany(LogAktivitas::class, 'user_id'); // Satu user punya banyak log
    }

    // Cek role pakai kolom 'role' di tabel users (untuk sidebar & tampilan)
    public function isAdmin() // Helper cek apakah user admin
    {
        return $this->role === 'admin'; // Bandingkan kolom role dengan 'admin'
    }

    public function isPetugas() // Helper cek apakah user petugas
    {
        return $this->role === 'petugas'; // Bandingkan kolom role dengan 'petugas'
    }

    public function isOwner() // Helper cek apakah user owner
    {
        return $this->role === 'owner'; // Bandingkan kolom role dengan 'owner'
    }

    // Cek permission pakai Spatie (untuk on/off fitur dari Manajemen Role)
    public function hasFeature($permission) // Helper cek permission via Spatie
    {
        return $this->hasPermissionTo($permission); // Delegasi ke method Spatie hasPermissionTo
    }
}