<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk query log
use App\Models\User; // Mengimpor model User untuk data dropdown filter
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal

class LogAktivitasController extends Controller // Mendefinisikan controller log aktivitas
{
    public function index(Request $request) // Method untuk menampilkan daftar log aktivitas dengan filter
    {
        $query = LogAktivitas::with('user'); // Query dasar dengan eager load relasi user

        // Filter tanggal
        if ($request->start_date && $request->end_date) { // Jika kedua tanggal diisi
            $start = Carbon::parse($request->start_date)->startOfDay(); // Parse tanggal mulai ke awal hari (00:00:00)
            $end = Carbon::parse($request->end_date)->endOfDay(); // Parse tanggal akhir ke akhir hari (23:59:59)
            $query->whereBetween('created_at', [$start, $end]); // Filter log dalam rentang tanggal
        }

        // Filter user
        if ($request->user_id) { // Jika filter user dipilih
            $query->where('user_id', $request->user_id); // Filter berdasarkan user_id
        }

        // Filter search
        if ($request->search) { // Jika ada kata kunci pencarian
            $query->where('aktivitas', 'like', '%' . $request->search . '%') // Cari di kolom aktivitas
                ->orWhereHas('user', function ($q) use ($request) { // Atau cari di relasi user
                    $q->where('nama', 'like', '%' . $request->search . '%'); // Kondisi nama user mengandung kata kunci
                });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20); // Urutkan terbaru, paginasi 20 per halaman

        // Summary
        $totalLogs = LogAktivitas::count(); // Hitung total seluruh log
        $todayLogs = LogAktivitas::whereDate('created_at', today())->count(); // Hitung log yang dibuat hari ini
        $activeUsers = LogAktivitas::distinct('user_id')->count('user_id'); // Hitung jumlah user unik yang punya log
        $users = User::orderBy('nama')->get(); // Ambil semua user untuk dropdown filter (urut nama)

        return view('log_aktivitas.index', compact('logs', 'totalLogs', 'todayLogs', 'activeUsers', 'users')); // Kirim data ke view
    }

    public function show($id) // Method untuk menampilkan detail satu log
    {
        $log = LogAktivitas::with('user')->findOrFail($id); // Cari log berdasarkan ID, error 404 jika tidak ada
        return view('log_aktivitas.show', compact('log')); // Kirim data ke view log_aktivitas.show
    }

    /**
     * Hapus SEMUA log aktivitas. Route ini sudah dibungkus middleware
     * checkRole:owner,admin di web.php, jadi cuma owner & admin yang
     * bisa sampai ke method ini. Dobel-cek role di sini sebagai jaring
     * pengaman kalau suatu saat middleware-nya berubah/lupa dipasang.
     */
    public function destroyAll(Request $request) // Method untuk menghapus seluruh log aktivitas
    {
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) { // Cek role: hanya owner atau admin yang boleh
            abort(403, 'Anda tidak memiliki izin untuk menghapus log aktivitas.'); // Tolak dengan HTTP 403 jika tidak berhak
        }

        $jumlah = LogAktivitas::count(); // Hitung jumlah log sebelum dihapus (untuk pesan sukses)
        LogAktivitas::truncate(); // Hapus semua baris di tabel log_aktivitas sekaligus

        return redirect()->route('log_aktivitas.index') // Redirect ke halaman index log
            ->with('success', "Berhasil menghapus {$jumlah} log aktivitas."); // Pesan sukses dengan jumlah log yang dihapus
    }
}