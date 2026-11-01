<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\AreaParkirController;
use App\Http\Controllers\TarifParkirController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==========================================
    // MANAJEMEN ROLE (Owner & Admin - hardcode)
    // ==========================================
    Route::middleware(['checkRole:admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    });

    // ==========================================
    // LAPORAN — permission: rekap_laporan
    // ==========================================
        Route::middleware(['checkPermission:rekap_laporan'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    
    // TAMBAHKAN BARIS INI UNTUK EXCEL
    Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
    });

    // ==========================================
    // LOG AKTIVITAS
    // ==========================================
    Route::middleware(['checkPermission:akses_log'])->group(function () {
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log_aktivitas.index');
        Route::get('/log-aktivitas/{id}', [LogAktivitasController::class, 'show'])->name('log_aktivitas.show');
    });

    // Hapus semua log — hardcode owner & admin
    Route::middleware(['checkRole:owner,admin'])->group(function () {
        Route::delete('/log-aktivitas', [LogAktivitasController::class, 'destroyAll'])->name('log_aktivitas.destroy-all');
    });

    // ==========================================
    // CRUD MASTER DATA — permission dinamis
    // ==========================================
    Route::middleware(['checkPermission:kelola_kendaraan'])->group(function () {
        Route::resource('kendaraan', KendaraanController::class);
    });

    Route::middleware(['checkPermission:crud_area'])->group(function () {
        Route::resource('area', AreaParkirController::class);
    });

    Route::middleware(['checkPermission:crud_tarif'])->group(function () {
        Route::resource('tarif', TarifParkirController::class);
    });

    Route::middleware(['checkPermission:crud_user'])->group(function () {
        Route::resource('pengguna', UserController::class);
    });

    // ==========================================
    // TRANSAKSI — URUTAN PENTING!
    // Route spesifik HARUS di atas route dinamis {id}
    // ==========================================

    // 1. Kendaraan masuk — permission: input_kendaraan
    Route::middleware(['checkPermission:input_kendaraan'])->group(function () {
        Route::get('/transaksi/masuk', [TransaksiController::class, 'masuk'])->name('transaksi.masuk');
        Route::post('/transaksi/masuk', [TransaksiController::class, 'prosesMasuk'])->name('transaksi.proses-masuk');
    });

    // 2. Kendaraan keluar — permission: output_kendaraan
    Route::middleware(['checkPermission:output_kendaraan'])->group(function () {
        Route::get('/transaksi/keluar', [TransaksiController::class, 'keluar'])->name('transaksi.keluar');
        Route::post('/transaksi/keluar', [TransaksiController::class, 'prosesKeluar'])->name('transaksi.proses-keluar');
    });

    // 3. Cetak & lihat struk — permission: cetak_struk
    Route::middleware(['checkPermission:cetak_struk'])->group(function () {
        Route::get('/transaksi/cetak/{id}', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk');
        Route::get('/transaksi/struk/{id}', [TransaksiController::class, 'struk'])->name('transaksi.struk');
    });

    // 4. Daftar & detail transaksi — permission: transaksi (paling BAWAH)
    Route::middleware(['checkPermission:transaksi'])->group(function () {
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');
        Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
    });

    // ==========================================
    // FITUR UMUM (Semua Role)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/delete-photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');

    Route::get('/search', [SearchController::class, 'search'])->name('search');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
});

// Debug route
Route::get('/debug-transaksi', function () {
    $semua = \App\Models\Transaksi::all();
    $keluar = \App\Models\Transaksi::where('status', 'keluar')->get();

    return response()->json([
        'semua_transaksi' => $semua->map(function($item) {
            return [
                'id' => $item->id,
                'kode' => $item->kode_transaksi,
                'status' => $item->status,
                'tarif' => $item->tarif,
                'waktu_masuk' => $item->waktu_masuk,
                'waktu_keluar' => $item->waktu_keluar,
            ];
        }),
        'total_keluar' => $keluar->count(),
        'total_pendapatan' => $keluar->sum('tarif'),
    ]);
});