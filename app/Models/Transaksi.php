<?php

namespace App\Models; // Menentukan namespace model

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model Eloquent

class Transaksi extends Model // Mendefinisikan model Transaksi
{
    use HasFactory; // Mengaktifkan trait HasFactory

    protected $table = 'transaksi'; // Menentukan nama tabel eksplisit

    protected $fillable = [ // Mendaftar kolom yang boleh diisi massal
        'kode_transaksi', // Kode unik transaksi (format TRX-YYYYMMDD-NNN)
        'kendaraan_id', // ID kendaraan yang diparkir
        'area_id', // ID area parkir
        'petugas_id', // ID petugas yang memproses
        'waktu_masuk', // Waktu kendaraan masuk
        'waktu_keluar', // Waktu kendaraan keluar (null jika masih masuk)
        'tarif', // Tarif (per hari saat masuk, total saat keluar)
        'status', // Status transaksi (masuk/keluar)
    ];

    protected $casts = [ // Konversi tipe otomatis saat akses atribut
        'waktu_masuk' => 'datetime', // Cast ke objek Carbon
        'waktu_keluar' => 'datetime', // Cast ke objek Carbon
        'tarif' => 'decimal:2', // Cast ke desimal 2 angka di belakang koma
    ];

    public function kendaraan() // Relasi ke model Kendaraan
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id'); // Transaksi milik satu kendaraan
    }

    public function areaParkir() // Relasi ke model AreaParkir
    {
        return $this->belongsTo(AreaParkir::class, 'area_id'); // Transaksi terjadi di satu area
    }

    public function petugas() // Relasi ke model User (petugas)
    {
        return $this->belongsTo(User::class, 'petugas_id'); // Transaksi diproses oleh satu petugas
    }
}