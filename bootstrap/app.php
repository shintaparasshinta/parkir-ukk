<?php

use Illuminate\Foundation\Application; // Mengimpor kelas Application sebagai entry point aplikasi Laravel
use Illuminate\Foundation\Configuration\Exceptions; // Mengimpor kelas konfigurasi penanganan exception
use Illuminate\Foundation\Configuration\Middleware; // Mengimpor kelas konfigurasi middleware
use Illuminate\Http\Request; // Mengimpor kelas Request untuk pengecekan tipe request

return Application::configure(basePath: dirname(__DIR__)) // Membuat instance aplikasi dengan base path = direktori induk dari folder bootstrap
    ->withRouting( // Konfigurasi routing aplikasi
        web: __DIR__.'/../routes/web.php', // Daftarkan file route web (dengan session, CSRF, dll)
        commands: __DIR__.'/../routes/console.php', // Daftarkan file route console (command artisan terjadwal/closure)
        health: '/up', // Endpoint health check di URL /up (berguna untuk monitoring/load balancer)
    )
    ->withMiddleware(function (Middleware $middleware): void { // Konfigurasi middleware global
        $middleware->alias([ // Daftarkan alias middleware agar bisa dipakai dengan nama pendek di route
            'checkRole' => \App\Http\Middleware\CheckRole::class, // Alias 'checkRole' → middleware CheckRole (cek role user)
            'checkPermission' => \App\Http\Middleware\CheckPermission::class, // Alias 'checkPermission' → middleware CheckPermission (cek fitur/permission)
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void { // Konfigurasi penanganan exception
        $exceptions->shouldRenderJsonWhen( // Atur kapan exception dirender sebagai JSON (bukan HTML)
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(), // Render JSON jika request ke /api/* atau klien mengharapkan JSON
        );
    })->create(); // Finalisasi dan kembalikan instance aplikasi