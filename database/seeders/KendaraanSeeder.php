<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Mengimpor trait untuk menonaktifkan event model (tidak dipakai)
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use App\Models\Kendaraan; // Mengimpor model Kendaraan

class KendaraanSeeder extends Seeder // Mendefinisikan seeder untuk tabel kendaraan
{
    /**
     * Run the database seeds.
     */
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        // Data kendaraan motor
        Kendaraan::create([ // Membuat kendaraan motor pertama
            'plat_nomor' => 'B 1234 ABC', // Plat nomor unik
            'pemilik' => 'Ahmad Fauzi', // Nama pemilik
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'merk' => 'Honda', // Merk kendaraan
            'warna' => 'Merah', // Warna kendaraan
        ]);

        Kendaraan::create([ // Membuat kendaraan motor kedua
            'plat_nomor' => 'B 5678 DEF', // Plat nomor unik
            'pemilik' => 'Dewi Lestari', // Nama pemilik
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'merk' => 'Yamaha', // Merk kendaraan
            'warna' => 'Biru', // Warna kendaraan
        ]);

        Kendaraan::create([ // Membuat kendaraan motor ketiga
            'plat_nomor' => 'B 9012 GHI', // Plat nomor unik
            'pemilik' => 'Rudi Hartono', // Nama pemilik
            'jenis_kendaraan' => 'Motor', // Jenis: Motor
            'merk' => 'Suzuki', // Merk kendaraan
            'warna' => 'Hitam', // Warna kendaraan
        ]);

        // Data kendaraan mobil
        Kendaraan::create([ // Membuat kendaraan mobil pertama
            'plat_nomor' => 'B 3456 JKL', // Plat nomor unik
            'pemilik' => 'Siti Nurhaliza', // Nama pemilik
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'merk' => 'Toyota', // Merk kendaraan
            'warna' => 'Putih', // Warna kendaraan
        ]);

        Kendaraan::create([ // Membuat kendaraan mobil kedua
            'plat_nomor' => 'B 7890 MNO', // Plat nomor unik
            'pemilik' => 'Agus Salim', // Nama pemilik
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'merk' => 'Honda', // Merk kendaraan
            'warna' => 'Silver', // Warna kendaraan
        ]);

        Kendaraan::create([ // Membuat kendaraan mobil ketiga
            'plat_nomor' => 'B 2345 PQR', // Plat nomor unik
            'pemilik' => 'Rina Anggraeni', // Nama pemilik
            'jenis_kendaraan' => 'Mobil', // Jenis: Mobil
            'merk' => 'Suzuki', // Merk kendaraan
            'warna' => 'Hitam', // Warna kendaraan
        ]);

        $this->command->info('✅ Kendaraan seeder berhasil dijalankan!'); // Pesan sukses
        $this->command->info('📌 Total 6 kendaraan (3 motor, 3 mobil)'); // Info jumlah kendaraan
    }
}