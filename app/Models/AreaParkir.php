<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    use HasFactory;

    protected $table = 'area_parkir';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'jenis_kendaraan',
        'status',
    ];

    public function tarifParkir()
    {
        return $this->hasMany(TarifParkir::class, 'area_id');
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'area_id');
    }
}