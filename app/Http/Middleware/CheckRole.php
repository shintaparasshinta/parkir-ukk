<?php

namespace App\Http\Middleware; // Menentukan namespace middleware

use Closure; // Mengimpor kelas Closure untuk menerima parameter $next (middleware berikutnya)
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request

class CheckRole // Mendefinisikan middleware untuk cek role berbasis kolom `role`
{
    public function handle(Request $request, Closure $next, ...$roles) // Method utama middleware, menerima daftar role yang diizinkan (variadic)
    {
        if (!auth()->check()) { // Jika pengguna belum login
            return redirect('/login'); // Redirect ke halaman login
        }

        // Cek berdasarkan kolom 'role' di tabel users
        if (in_array(auth()->user()->role, $roles)) { // Jika role pengguna ada di daftar role yang diizinkan
            return $next($request); // Lanjutkan request ke middleware/controller berikutnya
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.'); // Tolak dengan HTTP 403 jika role tidak berhak
    }
}