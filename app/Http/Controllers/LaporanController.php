<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\Transaksi; // Mengimpor model Transaksi untuk query data transaksi
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk filter & dropdown area
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Carbon\Carbon; // Mengimpor Carbon untuk format tanggal
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk query raw (DATE, SUM, CASE, COALESCE)

class LaporanController extends Controller // Mendefinisikan controller laporan
{
    public function index(Request $request) // Method untuk menampilkan laporan transaksi dengan filter
    {
        $startDate = $request->input('start_date'); // Mengambil filter tanggal mulai
        $endDate = $request->input('end_date'); // Mengambil filter tanggal akhir
        $areaId = $request->input('area_id'); // Mengambil filter area parkir
        $jenisKendaraan = $request->input('jenis_kendaraan'); // Mengambil filter jenis kendaraan

        // Query dasar
        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']); // Query dasar dengan eager load relasi

        // Filter berdasarkan tanggal (waktu masuk ATAU waktu keluar)
        if ($startDate && $endDate) { // Jika kedua tanggal diisi
            $query->where(function($q) use ($startDate, $endDate) { // Mengelompokkan kondisi OR agar tidak bocor dari filter lain
                $q->whereBetween('waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']) // Transaksi yang masuk dalam rentang
                  ->orWhereBetween('waktu_keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']); // Atau yang keluar dalam rentang
            });
        }
        if ($areaId) { // Jika filter area dipilih
            $query->where('area_id', $areaId); // Filter berdasarkan area_id
        }
        if ($jenisKendaraan) { // Jika filter jenis kendaraan dipilih
            $query->whereHas('kendaraan', function($q) use ($jenisKendaraan) { // Filter relasi kendaraan
                $q->where('jenis_kendaraan', $jenisKendaraan); // Kondisi jenis kendaraan sesuai filter
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->paginate(10); // Urutkan terbaru, paginasi 10 per halaman

        // Hitung total
        $totalTransaksi = $transaksi->total(); // Total seluruh transaksi (dari paginator)
        
        // Hitung masuk berdasarkan waktu_masuk, keluar berdasarkan waktu_keluar
        $totalMasuk = 0; // Inisialisasi total masuk
        $totalKeluar = 0; // Inisialisasi total keluar
        $totalPendapatan = 0; // Inisialisasi total pendapatan

        if ($startDate && $endDate) { // Jika filter tanggal diisi
            $totalMasuk = (clone $query)->whereBetween('waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(); // Hitung total masuk dalam rentang
            $totalKeluar = (clone $query)->where('status', 'keluar')->whereBetween('waktu_keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->count(); // Hitung total keluar dalam rentang
            $totalPendapatan = (clone $query)->where('status', 'keluar')->whereBetween('waktu_keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->sum('tarif'); // Jumlahkan tarif transaksi keluar dalam rentang
        } else { // Jika tidak ada filter tanggal
            $totalMasuk = (clone $query)->count(); // Hitung seluruh transaksi (semua dianggap masuk)
            $totalKeluar = (clone $query)->where('status', 'keluar')->count(); // Hitung seluruh transaksi keluar
            $totalPendapatan = (clone $query)->where('status', 'keluar')->sum('tarif'); // Jumlahkan seluruh tarif transaksi keluar
        }

        // ================ DATA UNTUK GRAFIK ================
        $chartQuery = Transaksi::query(); // Query baru khusus untuk grafik (tanpa eager load)

        if ($startDate && $endDate) { // Terapkan ulang filter tanggal
            $chartQuery->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                  ->orWhereBetween('waktu_keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });
        }
        if ($areaId) { // Terapkan ulang filter area
            $chartQuery->where('area_id', $areaId);
        }
        if ($jenisKendaraan) { // Terapkan ulang filter jenis kendaraan
            $chartQuery->whereHas('kendaraan', function($q) use ($jenisKendaraan) {
                $q->where('jenis_kendaraan', $jenisKendaraan);
            });
        }

        // Gunakan COALESCE untuk mengambil tanggal keluar, kalau null ambil tanggal masuk
        $chartData = $chartQuery // Query agregasi untuk grafik
            ->select(
                DB::raw('DATE(COALESCE(waktu_keluar, waktu_masuk)) as tanggal'), // Ambil tanggal keluar, fallback ke tanggal masuk
                DB::raw('SUM(CASE WHEN DATE(waktu_masuk) BETWEEN "' . ($startDate ?? '1970-01-01') . '" AND "' . ($endDate ?? '2099-12-31') . '" THEN 1 ELSE 0 END) as masuk'), // Hitung jumlah masuk per tanggal
                DB::raw('SUM(CASE WHEN status = "keluar" AND DATE(waktu_keluar) BETWEEN "' . ($startDate ?? '1970-01-01') . '" AND "' . ($endDate ?? '2099-12-31') . '" THEN 1 ELSE 0 END) as keluar'), // Hitung jumlah keluar per tanggal
                DB::raw('SUM(CASE WHEN status = "keluar" AND DATE(waktu_keluar) BETWEEN "' . ($startDate ?? '1970-01-01') . '" AND "' . ($endDate ?? '2099-12-31') . '" THEN tarif ELSE 0 END) as pendapatan') // Jumlahkan pendapatan per tanggal
            )
            ->groupBy('tanggal') // Kelompokkan per tanggal
            ->orderBy('tanggal', 'asc') // Urutkan tanggal naik
            ->get(); // Eksekusi query

        $chartLabelsFormatted = $chartData->map(function($item) { // Transformasi label tanggal
            return Carbon::parse($item->tanggal)->format('d/m'); // Format tanggal jadi dd/mm
        });
        $chartMasuk = $chartData->pluck('masuk'); // Ambil kolom masuk sebagai collection
        $chartKeluar = $chartData->pluck('keluar'); // Ambil kolom keluar sebagai collection
        $chartPendapatan = $chartData->pluck('pendapatan'); // Ambil kolom pendapatan sebagai collection

        $areas = AreaParkir::all(); // Ambil semua area parkir untuk dropdown filter

        return view('laporan.index', compact( // Kirim seluruh variabel ke view laporan.index
            'transaksi', 'startDate', 'endDate', 'areaId', 'jenisKendaraan',
            'totalTransaksi', 'totalMasuk', 'totalKeluar', 'totalPendapatan',
            'chartLabelsFormatted', 'chartMasuk', 'chartKeluar', 'chartPendapatan', 'areas'
        ));
    }

    public function export(Request $request) // Method untuk ekspor laporan (versi view/HTML/cetak)
    {
        $startDate = $request->input('start_date'); // Mengambil filter tanggal mulai
        $endDate = $request->input('end_date'); // Mengambil filter tanggal akhir
        $areaId = $request->input('area_id'); // Mengambil filter area parkir
        $jenisKendaraan = $request->input('jenis_kendaraan'); // Mengambil filter jenis kendaraan

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']); // Query dasar dengan relasi

        if ($startDate && $endDate) { // Terapkan filter rentang tanggal (masuk ATAU keluar)
            $query->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('waktu_masuk', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                  ->orWhereBetween('waktu_keluar', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            });
        }
        if ($areaId) { // Terapkan filter area
            $query->where('area_id', $areaId);
        }
        if ($jenisKendaraan) { // Terapkan filter jenis kendaraan
            $query->whereHas('kendaraan', function($q) use ($jenisKendaraan) {
                $q->where('jenis_kendaraan', $jenisKendaraan);
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get(); // Ambil semua data (tanpa paginasi) untuk ekspor

        return view('laporan.export', compact('transaksi', 'startDate', 'endDate')); // Tampilkan view laporan.export
    }

    public function exportExcel(Request $request) // Method untuk ekspor laporan ke file Excel (.xlsx)
    {
        $startDate = $request->input('start_date'); // Mengambil filter tanggal mulai
        $endDate = $request->input('end_date'); // Mengambil filter tanggal akhir
        $areaId = $request->input('area_id'); // Mengambil filter area parkir
        $jenisKendaraan = $request->input('jenis_kendaraan'); // Mengambil filter jenis kendaraan

        $filename = 'Laporan_Parkir_' . date('Ymd_His') . '.xlsx'; // Membuat nama file dengan timestamp

        return \Maatwebsite\Excel\Facades\Excel::download( // Mengunduh file Excel via package Maatwebsite
            new \App\Exports\LaporanExport($startDate, $endDate, $areaId, $jenisKendaraan), // Instansiasi class export dengan filter
            $filename // Nama file unduhan
        );
    }
}