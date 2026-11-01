<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil semua seeder dengan urutan yang benar
        $this->call([
            UserSeeder::class,           // 1. Users dulu
            KendaraanSeeder::class,      // 2. Kendaraan
            AreaParkirSeeder::class,     // 3. Area Parkir
            TarifParkirSeeder::class,    // 4. Tarif Parkir (tergantung area_id)
            // Transaksi dan LogAktivitas tidak perlu di-seed awal
        ]);

        $this->command->info('✅ Semua seeder selesai dijalankan!');
        $this->command->info('🎉 Database siap digunakan!');
    }
}