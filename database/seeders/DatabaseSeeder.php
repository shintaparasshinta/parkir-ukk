<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder

class DatabaseSeeder extends Seeder // Seeder utama yang mengorkestrasi semua seeder lain
{
    public function run(): void // Method utama yang dipanggil saat `php artisan db:seed`
    {
        // Urutan sangat penting! Role harus dibuat sebelum User
        $this->call([ // Panggil array seeder secara berurutan
            RolePermissionSeeder::class, // 1. Buat Role & Permission DULU (Spatie butuh ini sebelum assignRole)
            AreaParkirSeeder::class,     // 3. Data Master — area parkir
            KendaraanSeeder::class,      // 4. Data Master — kendaraan
            TarifParkirSeeder::class,    // 5. Tarif (butuh area_id dari AreaParkirSeeder)
        ]);
    }
}