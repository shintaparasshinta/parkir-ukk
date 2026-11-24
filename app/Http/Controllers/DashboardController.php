<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request (meski tidak dipakai langsung di sini)
use App\Models\User; // Mengimpor model User untuk hitung total pengguna
use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk hitung total kendaraan
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk data area & status
use App\Models\Transaksi; // Mengimpor model Transaksi untuk data transaksi & grafik
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal/waktu

class DashboardController extends Controller // Mendefinisikan controller dashboard
{
    public function index() // Method untuk menampilkan halaman dashboard
    {
        // Data untuk cards
        $total_users = User::count(); // Menghitung total seluruh pengguna
        $total_kendaraan = Kendaraan::count(); // Menghitung total seluruh kendaraan
        $total_area = AreaParkir::count(); // Menghitung total seluruh area parkir
        $transaksi_hari_ini = Transaksi::whereDate('created_at', today())->count(); // Menghitung transaksi yang dibuat hari ini

        // Status area parkir
        $area_status = AreaParkir::all(); // Mengambil seluruh data area parkir untuk ditampilkan statusnya

        // Transaksi terbaru (5 data)
        $transaksi_terbaru = Transaksi::with(['kendaraan', 'areaParkir']) // Eager load relasi kendaraan & areaParkir
            ->orderBy('created_at', 'desc') // Urutkan dari transaksi terbaru
            ->limit(5) // Batasi hanya 5 data
            ->get(); // Eksekusi query

        // ================ DATA UNTUK GRAFIK ================
        // 7 hari terakhir
        $labels = []; // Array untuk label sumbu X (tanggal)
        $masukData = []; // Array untuk jumlah kendaraan masuk per hari
        $keluarData = []; // Array untuk jumlah kendaraan keluar per hari
        $pendapatanData = []; // Array untuk total pendapatan per hari

        for ($i = 6; $i >= 0; $i--) { // Perulangan 7 hari ke belakang (hari ini sampai 6 hari lalu)
            $date = Carbon::today()->subDays($i); // Menghitung tanggal: hari ini dikurangi i hari
            $dateStr = $date->format('Y-m-d'); // Format tanggal untuk query database
            $label = $date->format('d/m'); // Format tanggal untuk label grafik (dd/mm)

            $labels[] = $label; // Menambahkan label ke array

            // Hitung masuk
            $masuk = Transaksi::whereDate('created_at', $dateStr) // Filter transaksi pada tanggal tertentu
                ->where('status', 'masuk') // Filter status masuk
                ->count(); // Hitung jumlahnya
            $masukData[] = $masuk; // Simpan hasil ke array

            // Hitung keluar
            $keluar = Transaksi::whereDate('created_at', $dateStr) // Filter transaksi pada tanggal tertentu
                ->where('status', 'keluar') // Filter status keluar
                ->count(); // Hitung jumlahnya
            $keluarData[] = $keluar; // Simpan hasil ke array

            // Hitung pendapatan
            $pendapatan = Transaksi::whereDate('created_at', $dateStr) // Filter transaksi pada tanggal tertentu
                ->where('status', 'keluar') // Hanya transaksi keluar yang menghasilkan tarif
                ->sum('tarif'); // Jumlahkan kolom tarif
            $pendapatanData[] = (float) $pendapatan; // Cast ke float agar konsisten untuk grafik
        }

        $chart_data = [ // Menggabungkan seluruh data grafik ke satu array
            'labels' => $labels, // Label tanggal
            'masuk' => $masukData, // Data masuk
            'keluar' => $keluarData, // Data keluar
            'pendapatan' => $pendapatanData, // Data pendapatan
        ];

        return view('dashboard.index', compact( // Mengirim semua variabel ke view dashboard.index
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