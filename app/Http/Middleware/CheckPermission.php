<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
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
    public function handle(Request $request, Closure $next, string $permission)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->hasFeature($permission)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}