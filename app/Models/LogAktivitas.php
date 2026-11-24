<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class LogAktivitas extends Model // Mendefinisikan model LogAktivitas
{
    use HasFactory; // Mengaktifkan trait HasFactory

    protected $table = 'log_aktivitas'; // Menentukan nama tabel eksplisit

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'user_id', // ID pengguna yang melakukan aktivitas
        'aktivitas', // Deskripsi aktivitas
    ];

    public function user() // Relasi ke model User
    {
        return $this->belongsTo(User::class, 'user_id'); // Log dimiliki oleh satu User
    }
}