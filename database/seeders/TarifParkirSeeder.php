<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TarifParkir;

class TarifParkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tarif untuk Area A (Motor)
        TarifParkir::create([
            'area_id' => 1,
            'jenis_kendaraan' => 'Motor',
            'tarif' => 2000,
        ]);

        // Tarif untuk Area B (Motor)
        TarifParkir::create([
            'area_id' => 2,
            'jenis_kendaraan' => 'Motor',
            'tarif' => 2000,
        ]);

        // Tarif untuk Area C (Mobil)
        TarifParkir::create([
            'area_id' => 3,
            'jenis_kendaraan' => 'Mobil',
            'tarif' => 5000,
        ]);

        // Tarif untuk Area D (Mobil)
        TarifParkir::create([
            'area_id' => 4,
            'jenis_kendaraan' => 'Mobil',
            'tarif' => 5000,
        ]);

        // Tarif untuk Area E (VIP) - bisa untuk motor dan mobil
        TarifParkir::create([
            'area_id' => 5,
            'jenis_kendaraan' => 'Motor',
            'tarif' => 5000,
        ]);

        TarifParkir::create([
            'area_id' => 5,
            'jenis_kendaraan' => 'Mobil',
            'tarif' => 10000,
        ]);

        $this->command->info('✅ Tarif Parkir seeder berhasil dijalankan!');
        $this->command->info('📌 Total 6 tarif parkir');
    }
}