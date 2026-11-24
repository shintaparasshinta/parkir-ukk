<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder

class DatabaseSeeder extends Seeder // Seeder utama yang mengorkestrasi semua seeder lain
{
    /**
     * Seed the application's database.
     */
    public function run(): void // Method utama yang dipanggil saat `php artisan db:seed`
    {
        // Panggil semua seeder dengan urutan yang benar
        $this->call([ // Panggil array seeder secara berurutan
            UserSeeder::class,           // 1. Users dulu (karena tabel lain punya FK ke users)
            KendaraanSeeder::class,      // 2. Kendaraan (dibutuhkan transaksi)
            AreaParkirSeeder::class,     // 3. Area Parkir (dibutuhkan tarif & transaksi)
            TarifParkirSeeder::class,    // 4. Tarif Parkir (tergantung area_id, harus setelah area)
            // Transaksi dan LogAktivitas tidak perlu di-seed awal
        ]);

        $this->command->info('✅ Semua seeder selesai dijalankan!'); // Pesan sukses setelah semua seeder selesai
        $this->command->info('🎉 Database siap digunakan!'); // Pesan akhir bahwa database siap
    }
}