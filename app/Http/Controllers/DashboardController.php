<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request (tidak dipakai langsung)
use App\Models\User; // Mengimpor model User untuk hitung total pengguna
use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk hitung total kendaraan
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk data area & status
use App\Models\Transaksi; // Mengimpor model Transaksi untuk data transaksi & grafik
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal

class DashboardController extends Controller // Mendefinisikan controller dashboard
{
    public function index() // Method untuk menampilkan halaman dashboard
    {
        // Data untuk cards
        $total_users = User::count(); // Menghitung total seluruh pengguna
        $total_kendaraan = Kendaraan::count(); // Menghitung total seluruh kendaraan
        $total_area = AreaParkir::count(); // Menghitung total seluruh area parkir
        // Transaksi hari ini dihitung berdasarkan yang masuk hari ini
        $transaksi_hari_ini = Transaksi::whereDate('waktu_masuk', today())->count(); // [BERUBAH] Sebelumnya pakai created_at, sekarang waktu_masuk

        // Status area parkir
        $area_status = AreaParkir::all(); // Mengambil seluruh data area parkir

        // Transaksi terbaru (5 data)
        $transaksi_terbaru = Transaksi::with(['kendaraan', 'areaParkir']) // Eager load relasi
            ->orderBy('created_at', 'desc') // Urutkan terbaru
            ->limit(5) // Batasi 5 data
            ->get(); // Eksekusi query

        // ================ DATA UNTUK GRAFIK ================
        $labels = []; // Array label sumbu X
        $masukData = []; // Array jumlah masuk per hari
        $keluarData = []; // Array jumlah keluar per hari
        $pendapatanData = []; // Array total pendapatan per hari

        for ($i = 6; $i >= 0; $i--) { // Loop 7 hari terakhir
            $date = Carbon::today()->subDays($i); // Tanggal: hari ini - i
            $dateStr = $date->format('Y-m-d'); // Format untuk query DB
            $label = $date->format('d/m'); // Format untuk label grafik

            $labels[] = $label; // Tambah label

            // Hitung masuk (Berdasarkan waktu_masuk)
            $masuk = Transaksi::whereDate('waktu_masuk', $dateStr)->count(); // [BERUBAH] Tidak lagi filter status='masuk', hitung semua yang masuk di tanggal itu
            $masukData[] = $masuk; // Simpan hasil

            // Hitung keluar (Berdasarkan waktu_keluar)
            $keluar = Transaksi::whereDate('waktu_keluar', $dateStr) // [BERUBAH] Pakai waktu_keluar
                ->where('status', 'keluar') // Filter status keluar
                ->count(); // Hitung jumlah
            $keluarData[] = $keluar; // Simpan hasil

            // Hitung pendapatan (Berdasarkan waktu_keluar)
            $pendapatan = Transaksi::whereDate('waktu_keluar', $dateStr) // [BERUBAH] Pakai waktu_keluar
                ->where('status', 'keluar') // Hanya transaksi keluar
                ->sum('tarif'); // Jumlahkan tarif
            $pendapatanData[] = (float) $pendapatan; // Cast ke float
        }

        $chart_data = [ // Gabungkan data grafik
            'labels' => $labels, // Label tanggal
            'masuk' => $masukData, // Data masuk
            'keluar' => $keluarData, // Data keluar
            'pendapatan' => $pendapatanData, // Data pendapatan
        ];

        return view('dashboard.index', compact( // Kirim ke view
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