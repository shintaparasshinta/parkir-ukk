<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Data untuk cards
        $total_users = User::count();
        $total_kendaraan = Kendaraan::count();
        $total_area = AreaParkir::count();
        $transaksi_hari_ini = Transaksi::whereDate('created_at', today())->count();

        // Status area parkir
        $area_status = AreaParkir::all();

        // Transaksi terbaru (5 data)
        $transaksi_terbaru = Transaksi::with(['kendaraan', 'areaParkir'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ================ DATA UNTUK GRAFIK ================
        // 7 hari terakhir
        $labels = [];
        $masukData = [];
        $keluarData = [];
        $pendapatanData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->format('d/m');

            $labels[] = $label;

            // Hitung masuk
            $masuk = Transaksi::whereDate('created_at', $dateStr)
                ->where('status', 'masuk')
                ->count();
            $masukData[] = $masuk;

            // Hitung keluar
            $keluar = Transaksi::whereDate('created_at', $dateStr)
                ->where('status', 'keluar')
                ->count();
            $keluarData[] = $keluar;

            // Hitung pendapatan
            $pendapatan = Transaksi::whereDate('created_at', $dateStr)
                ->where('status', 'keluar')
                ->sum('tarif');
            $pendapatanData[] = (float) $pendapatan;
        }

        $chart_data = [
            'labels' => $labels,
            'masuk' => $masukData,
            'keluar' => $keluarData,
            'pendapatan' => $pendapatanData,
        ];

        return view('dashboard.index', compact(
            'total_users',
            'total_kendaraan',
            'total_area',
            'transaksi_hari_ini',
            'area_status',
            'transaksi_terbaru',
            'chart_data'
        ));
    }
}