<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\AreaParkir;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $areaId = $request->input('area_id');
        $jenisKendaraan = $request->input('jenis_kendaraan');

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if ($areaId) {
            $query->where('area_id', $areaId);
        }
        if ($jenisKendaraan) {
            $query->whereHas('kendaraan', function($q) use ($jenisKendaraan) {
                $q->where('jenis_kendaraan', $jenisKendaraan);
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->paginate(10);

        // Hitung total
        $totalTransaksi = $transaksi->total();
        $totalMasuk = (clone $query)->where('status', 'masuk')->count();
        $totalKeluar = (clone $query)->where('status', 'keluar')->count();
        $totalPendapatan = (clone $query)->where('status', 'keluar')->sum('tarif');

        // Data untuk Chart (Query Baru yang Bersih)
        $chartQuery = Transaksi::query();

        if ($startDate && $endDate) {
            $chartQuery->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if ($areaId) {
            $chartQuery->where('area_id', $areaId);
        }
        if ($jenisKendaraan) {
            $chartQuery->whereHas('kendaraan', function($q) use ($jenisKendaraan) {
                $q->where('jenis_kendaraan', $jenisKendaraan);
            });
        }

        $chartData = $chartQuery
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(CASE WHEN status = "masuk" THEN 1 ELSE 0 END) as masuk'),
                DB::raw('SUM(CASE WHEN status = "keluar" THEN 1 ELSE 0 END) as keluar'),
                DB::raw('SUM(CASE WHEN status = "keluar" THEN tarif ELSE 0 END) as pendapatan')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartLabelsFormatted = $chartData->map(function($item) {
            return Carbon::parse($item->tanggal)->format('d/m');
        });
        $chartMasuk = $chartData->pluck('masuk');
        $chartKeluar = $chartData->pluck('keluar');
        $chartPendapatan = $chartData->pluck('pendapatan');

        $areas = AreaParkir::all();

        return view('laporan.index', compact(
            'transaksi', 'startDate', 'endDate', 'areaId', 'jenisKendaraan',
            'totalTransaksi', 'totalMasuk', 'totalKeluar', 'totalPendapatan',
            'chartLabelsFormatted', 'chartMasuk', 'chartKeluar', 'chartPendapatan', 'areas'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $areaId = $request->input('area_id');
        $jenisKendaraan = $request->input('jenis_kendaraan');

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        if ($areaId) {
            $query->where('area_id', $areaId);
        }
        if ($jenisKendaraan) {
            $query->whereHas('kendaraan', function($q) use ($jenisKendaraan) {
                $q->where('jenis_kendaraan', $jenisKendaraan);
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        return view('laporan.export', compact('transaksi', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $areaId = $request->input('area_id');
        $jenisKendaraan = $request->input('jenis_kendaraan');

        $filename = 'Laporan_Parkir_' . date('Ymd_His') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanExport($startDate, $endDate, $areaId, $jenisKendaraan),
            $filename
        );
    }
}