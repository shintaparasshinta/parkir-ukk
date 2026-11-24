<?php

namespace App\Console\Commands; // Menentukan namespace command console

use Illuminate\Console\Command; // Mengimpor kelas dasar Command untuk membuat artisan command
use App\Models\Transaksi; // Mengimpor model Transaksi untuk query data parkir
use App\Models\Notification; // Mengimpor model Notification untuk kirim notifikasi
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal

class KirimRingkasanHarian extends Command // Mendefinisikan command untuk kirim ringkasan harian
{
    protected $signature = 'notif:ringkasan-harian'; // Nama command yang dipanggil via artisan
    protected $description = 'Mengirim ringkasan pendapatan harian ke Owner'; // Deskripsi command (tampil di php artisan list)

    public function handle() // Method utama yang dijalankan saat command dieksekusi
    {
        $hariIni = Carbon::today(); // Ambil objek Carbon untuk tanggal hari ini

        // Masuk dihitung dari waktu_masuk
        $totalMasuk = Transaksi::whereDate('waktu_masuk', $hariIni)->count(); // Hitung kendaraan yang masuk hari ini
        
        // Keluar & Pendapatan dihitung dari waktu_keluar
        $totalKeluar = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'keluar')->count(); // Hitung kendaraan yang keluar hari ini
        $pendapatan = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'keluar')->sum('tarif'); // Jumlahkan tarif transaksi yang keluar hari ini

        // Susun pesan
        $pesan = "Hari ini tercatat {$totalMasuk} kendaraan masuk dan {$totalKeluar} kendaraan keluar. "; // Bagian pertama pesan: jumlah masuk & keluar
        $pesan .= "Total pendapatan: Rp " . number_format($pendapatan, 0, ',', '.') . "."; // Bagian kedua pesan: total pendapatan terformat

        // Kirim ke Owner
        Notification::kirimKeOwner( // Panggil helper model untuk broadcast notifikasi ke semua owner
            'Ringkasan Laporan Harian', // Judul notifikasi
            $pesan, // Isi pesan ringkasan
            'success', // Tipe notifikasi (warna hijau di UI)
            route('laporan.index') // Link ke halaman laporan
        );

        $this->info('Ringkasan harian berhasil dikirim ke Owner.'); // Tampilkan pesan sukses di console
    }
}