<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class Kendaraan extends Model // Mendefinisikan model Kendaraan
{
    use HasFactory; // Mengaktifkan trait HasFactory

    /**
     * Nama tabel yang digunakan
     */
    protected $table = 'kendaraan'; // Menentukan nama tabel eksplisit (tunggal)

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'plat_nomor', // Plat nomor kendaraan (unik)
        'pemilik', // Nama pemilik kendaraan
        'jenis_kendaraan', // Jenis kendaraan (Motor/Mobil)
        'merk', // Merk kendaraan
        'warna', // Warna kendaraan
    ];

    /**
     * Relasi: Kendaraan memiliki banyak transaksi
     */
    public function transaksi() // Relasi ke model Transaksi
    {
        return $this->hasMany(Transaksi::class, 'kendaraan_id');  // PERHATIKAN: "kendaraan_id" sesuai ERD
    }
}