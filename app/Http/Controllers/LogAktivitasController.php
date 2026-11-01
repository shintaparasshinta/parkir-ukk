<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        // Filter tanggal
        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        // Filter user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter search
        if ($request->search) {
            $query->where('aktivitas', 'like', '%' . $request->search . '%')
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%');
                });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Summary
        $totalLogs = LogAktivitas::count();
        $todayLogs = LogAktivitas::whereDate('created_at', today())->count();
        $activeUsers = LogAktivitas::distinct('user_id')->count('user_id');
        $users = User::orderBy('nama')->get();

        return view('log_aktivitas.index', compact('logs', 'totalLogs', 'todayLogs', 'activeUsers', 'users'));
    }

    public function show($id)
    {
        $log = LogAktivitas::with('user')->findOrFail($id);
        return view('log_aktivitas.show', compact('log'));
    }

    /**
     * Hapus SEMUA log aktivitas. Route ini sudah dibungkus middleware
     * checkRole:owner,admin di web.php, jadi cuma owner & admin yang
     * bisa sampai ke method ini. Dobel-cek role di sini sebagai jaring
     * pengaman kalau suatu saat middleware-nya berubah/lupa dipasang.
     */
    public function destroyAll(Request $request)
    {
        if (!auth()->user()->isOwner() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus log aktivitas.');
        }

        $jumlah = LogAktivitas::count();
        LogAktivitas::truncate();

        return redirect()->route('log_aktivitas.index')
            ->with('success', "Berhasil menghapus {$jumlah} log aktivitas.");
    }
}