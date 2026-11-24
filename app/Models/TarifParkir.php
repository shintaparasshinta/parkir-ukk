<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class TarifParkir extends Model // Mendefinisikan model TarifParkir
{
    use HasFactory; // Mengaktifkan trait HasFactory

    protected $table = 'tarif_parkir'; // Menentukan nama tabel eksplisit

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'area_id', // ID area parkir terkait
        'jenis_kendaraan', // Jenis kendaraan (Motor/Mobil)
        'tarif', // Nilai tarif per hari
    ];

    public function areaParkir() // Relasi ke model AreaParkir
    {
        return $this->belongsTo(AreaParkir::class, 'area_id'); // Tarif dimiliki oleh satu area parkir
    }
}