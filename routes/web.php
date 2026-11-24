<?php

use Illuminate\Support\Facades\Route; // Mengimpor facade Route untuk mendefinisikan route
use App\Http\Controllers\LandingController; // Mengimpor controller landing page
use App\Http\Controllers\AuthController; // Mengimpor controller autentikasi
use App\Http\Controllers\DashboardController; // Mengimpor controller dashboard
use App\Http\Controllers\KendaraanController; // Mengimpor controller kendaraan
use App\Http\Controllers\AreaParkirController; // Mengimpor controller area parkir
use App\Http\Controllers\TarifParkirController; // Mengimpor controller tarif parkir
use App\Http\Controllers\TransaksiController; // Mengimpor controller transaksi
use App\Http\Controllers\LaporanController; // Mengimpor controller laporan
use App\Http\Controllers\UserController; // Mengimpor controller user
use App\Http\Controllers\LogAktivitasController; // Mengimpor controller log aktivitas
use App\Http\Controllers\ProfileController; // Mengimpor controller profil
use App\Http\Controllers\SearchController; // Mengimpor controller pencarian
use App\Http\Controllers\NotificationController; // Mengimpor controller notifikasi
use App\Http\Controllers\RoleController; // Mengimpor controller role

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing'); // Halaman utama publik

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Form login (guest)
Route::post('/login', [AuthController::class, 'login']); // Proses login
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Proses logout

// Dashboard
Route::middleware(['auth'])->group(function () { // Grup route yang memerlukan login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // Halaman dashboard

    // ==========================================
    // MANAJEMEN ROLE (Owner & Admin - hardcode)
    // ==========================================
    Route::middleware(['checkRole:admin'])->group(function () { // Hanya admin yang boleh akses
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index'); // Halaman manajemen role
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update'); // Update permission role
    });

    // ==========================================
    // LAPORAN — permission: rekap_laporan
    // ==========================================
        Route::middleware(['checkPermission:rekap_laporan'])->group(function () { // Hanya user dengan fitur rekap_laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index'); // Halaman laporan
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export'); // Ekspor laporan (HTML/cetak)
    
    // TAMBAHKAN BARIS INI UNTUK EXCEL
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel'); // Ekspor laporan ke Excel
    });

    // ==========================================
    // LOG AKTIVITAS
    // ==========================================
    Route::middleware(['checkPermission:akses_log'])->group(function () { // Hanya user dengan fitur akses_log
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log_aktivitas.index'); // Daftar log
        Route::get('/log-aktivitas/{id}', [LogAktivitasController::class, 'show'])->name('log_aktivitas.show'); // Detail log
    });

    // Hapus semua log — hardcode owner & admin
    Route::middleware(['checkRole:owner,admin'])->group(function () { // Hanya owner & admin
        Route::delete('/log-aktivitas', [LogAktivitasController::class, 'destroyAll'])->name('log_aktivitas.destroy-all'); // Hapus semua log
    });

    // ==========================================
    // CRUD MASTER DATA — permission dinamis
    // ==========================================
    Route::middleware(['checkPermission:kelola_kendaraan'])->group(function () { // Fitur kelola_kendaraan
        Route::resource('kendaraan', KendaraanController::class); // CRUD kendaraan (resource)
    });

    Route::middleware(['checkPermission:crud_area'])->group(function () { // Fitur crud_area
        Route::resource('area', AreaParkirController::class); // CRUD area parkir (resource)
    });

    Route::middleware(['checkPermission:crud_tarif'])->group(function () { // Fitur crud_tarif
        Route::resource('tarif', TarifParkirController::class); // CRUD tarif parkir (resource)
    });

    Route::middleware(['checkPermission:crud_user'])->group(function () { // Fitur crud_user
        Route::resource('pengguna', UserController::class); // CRUD user (resource)
    });

    // ==========================================
    // TRANSAKSI — URUTAN PENTING!
    // Route spesifik HARUS di atas route dinamis {id}
    // ==========================================

    // 1. Kendaraan masuk — permission: input_kendaraan
    Route::middleware(['checkPermission:input_kendaraan'])->group(function () { // Fitur input_kendaraan
        Route::get('/transaksi/masuk', [TransaksiController::class, 'masuk'])->name('transaksi.masuk'); // Form kendaraan masuk
        Route::post('/transaksi/masuk', [TransaksiController::class, 'prosesMasuk'])->name('transaksi.proses-masuk'); // Proses masuk
    });

    // 2. Kendaraan keluar — permission: output_kendaraan
    Route::middleware(['checkPermission:output_kendaraan'])->group(function () { // Fitur output_kendaraan
        Route::get('/transaksi/keluar', [TransaksiController::class, 'keluar'])->name('transaksi.keluar'); // Form kendaraan keluar
        Route::post('/transaksi/keluar', [TransaksiController::class, 'prosesKeluar'])->name('transaksi.proses-keluar'); // Proses keluar
    });

    // 3. Cetak & lihat struk — permission: cetak_struk
    Route::middleware(['checkPermission:cetak_struk'])->group(function () { // Fitur cetak_struk
        Route::get('/transaksi/cetak/{id}', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk'); // Cetak struk
        Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'struk'])->name('transaksi.struk'); // Lihat struk
    });

    // 4. Daftar & detail transaksi — permission: transaksi (paling BAWAH)
    Route::middleware(['checkPermission:transaksi'])->group(function () { // Fitur transaksi
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index'); // Daftar transaksi
        Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail'); // Detail transaksi
        Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy'); // Hapus transaksi
    });

    // ==========================================
    // FITUR UMUM (Semua Role)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index'); // Halaman profil
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Update profil
    Route::get('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo'); // Hapus foto profil

    Route::get('/search', [SearchController::class, 'search'])->name('search'); // Pencarian global
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index'); // Daftar notifikasi
    Route::get('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read'); // Tandai satu dibaca
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read'); // Tandai semua dibaca
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count'); // Hitung belum dibaca
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent'); // 5 notifikasi terbaru
});

// Debug route
Route::get('/debug-transaksi', function () { // Route debug (TIDAK aman untuk production)
    $semua = \App\Models\Transaksi::all(); // Ambil semua transaksi
    $keluar = \App\Models\Transaksi::where('status', 'keluar')->get(); // Ambil transaksi yang sudah keluar

    return response()->json([ // Kembalikan response JSON
        'semua_transaksi' => $semua->map(function($item) { // Map tiap transaksi ke array ringkas
            return [
                'id' => $item->id, // ID transaksi
                'kode' => $item->kode_transaksi, // Kode transaksi
                'status' => $item->status, // Status
                'tarif' => $item->tarif, // Tarif
                'waktu_masuk' => $item->waktu_masuk, // Waktu masuk
                'waktu_keluar' => $item->waktu_keluar, // Waktu keluar
            ];
        }),
        'total_keluar' => $keluar->count(), // Total transaksi keluar
        'total_pendapatan' => $keluar->sum('tarif'), // Total pendapatan
    ]);
});