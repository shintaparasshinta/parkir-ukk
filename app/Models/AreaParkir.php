<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk dukungan factory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class AreaParkir extends Model // Mendefinisikan model AreaParkir
{
    use HasFactory; // Mengaktifkan trait HasFactory

    protected $table = 'area_parkir'; // Menentukan nama tabel eksplisit (tunggal, bukan plural default)

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'nama_area', // Nama area parkir
        'kapasitas', // Kapasitas maksimum area
        'jenis_kendaraan', // Jenis kendaraan yang dilayani (semua/Motor/Mobil)
        'status', // Status area (tersedia/hampir_penuh/penuh)
    ];

    public function tarifParkir() // Relasi ke model TarifParkir
    {
        return $this->hasMany(TarifParkir::class, 'area_id'); // Satu area punya banyak tarif (per jenis kendaraan)
    }

    public function transaksi() // Relasi ke model Transaksi
    {
        return $this->hasMany(Transaksi::class, 'area_id'); // Satu area punya banyak transaksi
    }
}