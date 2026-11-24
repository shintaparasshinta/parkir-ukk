<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaksi;
use App\Models\Notification;
use Carbon\Carbon;

class KirimRingkasanHarian extends Command
{
    protected $signature = 'notif:ringkasan-harian';
    protected $description = 'Mengirim ringkasan pendapatan harian ke Owner';

    public function handle()
    {
        $hariIni = Carbon::today();

        // Masuk dihitung dari waktu_masuk
        $totalMasuk = Transaksi::whereDate('waktu_masuk', $hariIni)->count();
        
        // Keluar & Pendapatan dihitung dari waktu_keluar
        $totalKeluar = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'keluar')->count();
        $pendapatan = Transaksi::whereDate('waktu_keluar', $hariIni)->where('status', 'keluar')->sum('tarif');

        // Susun pesan
        $pesan = "Hari ini tercatat {$totalMasuk} kendaraan masuk dan {$totalKeluar} kendaraan keluar. ";
        $pesan .= "Total pendapatan: Rp " . number_format($pendapatan, 0, ',', '.') . ".";

        // Kirim ke Owner
        Notification::kirimKeOwner(
            'Ringkasan Laporan Harian',
            $pesan,
            'success', 
            route('laporan.index')
        );

        $this->info('Ringkasan harian berhasil dikirim ke Owner.');
    }
}