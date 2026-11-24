<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Mengimpor trait untuk menonaktifkan event model saat seeding (tidak dipakai di sini)
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk membuat data area

class AreaParkirSeeder extends Seeder // Mendefinisikan seeder untuk tabel area_parkir
{
    /**
     * Run the database seeds.
     */
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        AreaParkir::create([ // Membuat area parkir pertama
            'nama_area' => 'Area A (Motor)', // Nama area A untuk motor
            'kapasitas' => 50, // Kapasitas 50 kendaraan
            'status' => 'tersedia', // Status awal: tersedia
        ]);

        AreaParkir::create([ // Membuat area parkir kedua
            'nama_area' => 'Area B (Motor)', // Nama area B untuk motor
            'kapasitas' => 40, // Kapasitas 40 kendaraan
            'status' => 'tersedia', // Status awal: tersedia
        ]);

        AreaParkir::create([ // Membuat area parkir ketiga
            'nama_area' => 'Area C (Mobil)', // Nama area C untuk mobil
            'kapasitas' => 30, // Kapasitas 30 kendaraan
            'status' => 'tersedia', // Status awal: tersedia
        ]);

        AreaParkir::create([ // Membuat area parkir keempat
            'nama_area' => 'Area D (Mobil)', // Nama area D untuk mobil
            'kapasitas' => 25, // Kapasitas 25 kendaraan
            'status' => 'tersedia', // Status awal: tersedia
        ]);

        AreaParkir::create([ // Membuat area parkir kelima
            'nama_area' => 'Area E (VIP)', // Nama area E (VIP)
            'kapasitas' => 10, // Kapasitas 10 kendaraan
            'status' => 'tersedia', // Status awal: tersedia
        ]);

        $this->command->info('✅ Area Parkir seeder berhasil dijalankan!'); // Tampilkan pesan sukses di console
        $this->command->info('📌 Total 5 area parkir'); // Tampilkan info jumlah area yang dibuat
    }
}