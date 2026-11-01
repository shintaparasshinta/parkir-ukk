<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan
     */
    protected $table = 'kendaraan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plat_nomor',
        'pemilik',
        'jenis_kendaraan',
        'merk',
        'warna',
    ];

    /**
     * Relasi: Kendaraan memiliki banyak transaksi
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'kendaraan_id');  // PERHATIKAN: "kendaraan_id" sesuai ERD
    }
}