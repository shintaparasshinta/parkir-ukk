<?php

namespace App\Providers; // Menentukan namespace provider

use Illuminate\Support\ServiceProvider; // Mengimpor kelas dasar ServiceProvider

class AppServiceProvider extends ServiceProvider // Mendefinisikan service provider utama aplikasi
{
    /**
     * Register any application services.
     */
    public function register(): void // Method untuk mendaftarkan binding ke container (dipanggil saat boot)
    {
        // // Kosong: belum ada service/binding khusus yang didaftarkan di aplikasi ini
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void // Method untuk menjalankan logika setelah semua service terdaftar
    {
        // // Kosong: belum ada bootstrapping khusus (misal event listener, view composer, dsb)
    }
}