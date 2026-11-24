<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Mengimpor trait untuk menonaktifkan event model (tidak dipakai)
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use App\Models\TarifParkir; // Mengimpor model TarifParkir

class TarifParkirSeeder extends Seeder // Mendefinisikan seeder untuk tabel tarif_parkir
{
    /**
     * Run the database seeds.
     */
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        // Tarif untuk Area A (Motor)
        TarifParkir::create([ // Membuat tarif area A
            'area_id' => 1, // ID area A (asumsi auto-increment dari AreaParkirSeeder)
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'tarif' => 2000, // Tarif Rp 2.000
        ]);

        // Tarif untuk Area B (Motor)
        TarifParkir::create([ // Membuat tarif area B
            'area_id' => 2, // ID area B
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'tarif' => 2000, // Tarif Rp 2.000
        ]);

        // Tarif untuk Area C (Mobil)
        TarifParkir::create([ // Membuat tarif area C
            'area_id' => 3, // ID area C
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'tarif' => 5000, // Tarif Rp 5.000
        ]);

        // Tarif untuk Area D (Mobil)
        TarifParkir::create([ // Membuat tarif area D
            'area_id' => 4, // ID area D
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'tarif' => 5000, // Tarif Rp 5.000
        ]);

        // Tarif untuk Area E (VIP) - bisa untuk motor dan mobil
        TarifParkir::create([ // Membuat tarif VIP untuk motor
            'area_id' => 5, // ID area E (VIP)
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'tarif' => 5000, // Tarif Rp 5.000 (lebih mahal karena VIP)
        ]);

        TarifParkir::create([ // Membuat tarif VIP untuk mobil
            'area_id' => 5, // ID area E (VIP)
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'tarif' => 10000, // Tarif Rp 10.000
        ]);

        $this->command->info('✅ Tarif Parkir seeder berhasil dijalankan!'); // Pesan sukses
        $this->command->info('📌 Total 6 tarif parkir'); // Info jumlah tarif
    }
}