<?php

use Illuminate\Foundation\Inspiring; // Mengimpor kelas Inspiring untuk quote motivasi bawaan Laravel
use Illuminate\Support\Facades\Artisan; // Mengimpor facade Artisan untuk mendaftarkan command custom
use Illuminate\Support\Facades\Schedule; // Mengimpor facade Schedule untuk mendaftarkan jadwal command

Schedule::command('notif:ringkasan-harian')->dailyAt('21:00'); // Jadwalkan command ringkasan harian setiap hari pukul 21:00

Artisan::command('inspire', function () { // Mendaftarkan command artisan 'inspire' (bawaan Laravel)
    $this->comment(Inspiring::quote()); // Tampilkan quote motivasi acak di console
})->purpose('Display an inspiring quote'); // Deskripsi command (tampil di php artisan list)    