<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifParkir extends Model
{
    use HasFactory;

    protected $table = 'tarif_parkir';

    protected $fillable = [
        'area_id',
        'jenis_kendaraan',
        'tarif',
    ];

    public function areaParkir()
    {
        return $this->belongsTo(AreaParkir::class, 'area_id');
    }
}
