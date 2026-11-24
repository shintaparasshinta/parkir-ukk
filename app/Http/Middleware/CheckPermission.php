<?php

namespace App\Http\Middleware; // Menentukan namespace middleware

use Closure; // Mengimpor kelas Closure untuk menerima parameter $next (middleware berikutnya)
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani HTTP request

class CheckPermission // Mendefinisikan middleware untuk cek permission berbasis toggle fitur
{
    /**
     * Cek apakah role user (owner/admin/petugas) punya permission
     * tertentu sesuai pengaturan di halaman Manajemen Role.
     *
     * Berbeda dari CheckRole (yang cuma cocokin kolom `role` ke daftar
     * role statis), middleware ini baca toggle permission yang
     * beneran diatur owner/admin lewat Manajemen Role, lewat
     * User::hasFeature().
     */
    public function handle(Request $request, Closure $next, string $permission) // Method utama middleware, menerima nama permission sebagai parameter
    {
        if (!auth()->check()) { // Jika pengguna belum login
            return redirect('/login'); // Redirect ke halaman login
        }

        if (auth()->user()->hasFeature($permission)) { // Jika pengguna memiliki fitur/permission yang diminta
            return $next($request); // Lanjutkan request ke middleware/controller berikutnya
        }

        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.'); // Tolak dengan HTTP 403 jika tidak berhak
    }
}