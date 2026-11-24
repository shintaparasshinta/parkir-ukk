<?php

use App\Providers\AppServiceProvider; // Mengimpor AppServiceProvider untuk didaftarkan

return [ // Kembalikan array berisi daftar service provider yang aktif
    AppServiceProvider::class, // Daftarkan AppServiceProvider sebagai provider utama aplikasi
];