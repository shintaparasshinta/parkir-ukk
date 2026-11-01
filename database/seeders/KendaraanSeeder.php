<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kendaraan;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kendaraan motor
        Kendaraan::create([
            'plat_nomor' => 'B 1234 ABC',
            'pemilik' => 'Ahmad Fauzi',
            'jenis_kendaraan' => 'Motor',
            'merk' => 'Honda',
            'warna' => 'Merah',
        ]);

        Kendaraan::create([
            'plat_nomor' => 'B 5678 DEF',
            'pemilik' => 'Dewi Lestari',
            'jenis_kendaraan' => 'Motor',
            'merk' => 'Yamaha',
            'warna' => 'Biru',
        ]);

        Kendaraan::create([
            'plat_nomor' => 'B 9012 GHI',
            'pemilik' => 'Rudi Hartono',
            'jenis_kendaraan' => 'Motor',
            'merk' => 'Suzuki',
            'warna' => 'Hitam',
        ]);

        // Data kendaraan mobil
        Kendaraan::create([
            'plat_nomor' => 'B 3456 JKL',
            'pemilik' => 'Siti Nurhaliza',
            'jenis_kendaraan' => 'Mobil',
            'merk' => 'Toyota',
            'warna' => 'Putih',
        ]);

        Kendaraan::create([
            'plat_nomor' => 'B 7890 MNO',
            'pemilik' => 'Agus Salim',
            'jenis_kendaraan' => 'Mobil',
            'merk' => 'Honda',
            'warna' => 'Silver',
        ]);

        Kendaraan::create([
            'plat_nomor' => 'B 2345 PQR',
            'pemilik' => 'Rina Anggraeni',
            'jenis_kendaraan' => 'Mobil',
            'merk' => 'Suzuki',
            'warna' => 'Hitam',
        ]);

        $this->command->info('✅ Kendaraan seeder berhasil dijalankan!');
        $this->command->info('📌 Total 6 kendaraan (3 motor, 3 mobil)');
    }
}