<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AreaParkir;

class AreaParkirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AreaParkir::create([
            'nama_area' => 'Area A (Motor)',
            'kapasitas' => 50,
            'status' => 'tersedia',
        ]);

        AreaParkir::create([
            'nama_area' => 'Area B (Motor)',
            'kapasitas' => 40,
            'status' => 'tersedia',
        ]);

        AreaParkir::create([
            'nama_area' => 'Area C (Mobil)',
            'kapasitas' => 30,
            'status' => 'tersedia',
        ]);

        AreaParkir::create([
            'nama_area' => 'Area D (Mobil)',
            'kapasitas' => 25,
            'status' => 'tersedia',
        ]);

        AreaParkir::create([
            'nama_area' => 'Area E (VIP)',
            'kapasitas' => 10,
            'status' => 'tersedia',
        ]);

        $this->command->info('✅ Area Parkir seeder berhasil dijalankan!');
        $this->command->info('📌 Total 5 area parkir');
    }
}