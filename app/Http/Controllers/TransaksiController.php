<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kendaraan;
use App\Models\AreaParkir;
use App\Models\TarifParkir;
use App\Models\LogAktivitas;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('transaksi.index', compact('transaksi'));
    }

    /**
     * Halaman Kendaraan Masuk
     * - Filter: kendaraan yang sedang parkir (status = 'masuk') TIDAK muncul di dropdown
     * - Area parkir + sisa kapasitas
     */
    public function masuk()
    {
        // 1. Ambil ID kendaraan yang sedang parkir (status = 'masuk')
        $kendaraanParkir = Transaksi::where('status', 'masuk')
            ->pluck('kendaraan_id')
            ->toArray();

        // 2. Hanya kendaraan yang TIDAK sedang parkir yang muncul di dropdown
        $kendaraan = Kendaraan::whereNotIn('id', $kendaraanParkir)
            ->orderBy('plat_nomor')
            ->get();

        // 3. Area parkir + sisa kapasitas
        $areaParkir = AreaParkir::where('status', '!=', 'penuh')
            ->orderBy('nama_area')
            ->get()
            ->map(function ($area) {
                $terisi = Transaksi::where('area_id', $area->id)
                    ->where('status', 'masuk')
                    ->count();
                $area->terisi = $terisi;
                $area->tersedia = max(0, $area->kapasitas - $terisi);
                return $area;
            });

        return view('transaksi.masuk', compact('kendaraan', 'areaParkir'));
    }

    public function prosesMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kendaraan_id' => 'required|exists:kendaraan,id',
            'area_id' => 'required|exists:area_parkir,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cek apakah kendaraan sudah parkir (safety check kalau ada yang bypass dropdown)
        $cekTransaksi = Transaksi::where('kendaraan_id', $request->kendaraan_id)
            ->where('status', 'masuk')
            ->first();

        if ($cekTransaksi) {
            return redirect()->back()
                ->with('error', 'Kendaraan ini sudah parkir! Silahkan proses keluar terlebih dahulu.')
                ->withInput();
        }

        $area = AreaParkir::find($request->area_id);
        $terisi = Transaksi::where('area_id', $area->id)->where('status', 'masuk')->count();
        if ($terisi >= $area->kapasitas) {
            return redirect()->back()
                ->with('error', 'Area ' . $area->nama_area . ' sudah penuh! Pilih area lain.')
                ->withInput();
        }

        // Generate kode transaksi
        $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad(Transaksi::count() + 1, 3, '0', STR_PAD_LEFT);
        $kendaraan = Kendaraan::find($request->kendaraan_id);

        // Ambil tarif PER HARI berdasarkan area & jenis kendaraan
        $tarif = TarifParkir::where('area_id', $request->area_id)
            ->where('jenis_kendaraan', $kendaraan->jenis_kendaraan)
            ->first();

        if (!$tarif) {
            return redirect()->back()
                ->with('error', 'Tarif untuk area ini dan jenis kendaraan belum diatur!')
                ->withInput();
        }

        // Buat transaksi (kolom tarif = tarif per hari)
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'kendaraan_id' => $request->kendaraan_id,
            'area_id' => $request->area_id,
            'petugas_id' => auth()->user()->id,
            'waktu_masuk' => Carbon::now(),
            'waktu_keluar' => null,
            'tarif' => $tarif->tarif,
            'status' => 'masuk',
        ]);

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Kendaraan masuk: ' . $kendaraan->plat_nomor . ' (' . $kendaraan->jenis_kendaraan . ') - Area: ' . $transaksi->areaParkir->nama_area . ' - Kode: ' . $kodeTransaksi,
        ]);

        // ============================================
        // KIRIM NOTIFIKASI KE ADMIN & PETUGAS SAJA
        // ============================================
        $users = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Kendaraan Masuk',
                'message' => 'Kendaraan ' . $kendaraan->plat_nomor . ' (' . $kendaraan->jenis_kendaraan . ') masuk ke ' . $transaksi->areaParkir->nama_area,
                'type' => 'success',
                'link' => route('transaksi.detail', $transaksi->id),
                'is_read' => false,
            ]);
        }

        // Update status area
        $totalKendaraan = Transaksi::where('area_id', $request->area_id)->where('status', 'masuk')->count();
        $area = AreaParkir::find($request->area_id);
        $persentase = ($totalKendaraan / $area->kapasitas) * 100;

        if ($persentase >= 100) {
            $area->update(['status' => 'penuh']);
        } elseif ($persentase >= 80) {
            $area->update(['status' => 'hampir_penuh']);
        }

        return redirect()->route('transaksi.struk', $transaksi->id)
            ->with('success', 'Kendaraan berhasil masuk! Kode transaksi: ' . $kodeTransaksi);
    }

    public function keluar()
    {
        $transaksiMasuk = Transaksi::with(['kendaraan', 'areaParkir'])
            ->where('status', 'masuk')
            ->orderBy('waktu_masuk')
            ->get();
        return view('transaksi.keluar', compact('transaksiMasuk'));
    }

    public function prosesKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaksi_id' => 'required|exists:transaksi,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $transaksi = Transaksi::with(['kendaraan', 'areaParkir'])->find($request->transaksi_id);

        if (!$transaksi || $transaksi->status == 'keluar') {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau sudah selesai!');
        }

        // ============================================
        // HITUNG DURASI PARKIR PER HARI
        // ============================================
        $waktuMasuk = Carbon::parse($transaksi->waktu_masuk)->startOfDay();
        $waktuKeluar = Carbon::now()->startOfDay();

        $durasiHari = $waktuMasuk->diffInDays($waktuKeluar) + 1;
        if ($durasiHari < 1) $durasiHari = 1;

        $tarifPerHari = $transaksi->tarif;
        $totalTarif = $durasiHari * $tarifPerHari;

        // UPDATE transaksi
        DB::table('transaksi')
            ->where('id', $transaksi->id)
            ->update([
                'waktu_keluar' => Carbon::now(),
                'tarif' => $totalTarif,
                'status' => 'keluar',
                'updated_at' => now(),
            ]);

        $transaksi->refresh();

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Kendaraan keluar: ' . $transaksi->kendaraan->plat_nomor . ' - Durasi: ' . $durasiHari . ' hari - Total bayar Rp ' . number_format($totalTarif, 0, ',', '.') . ' - Kode: ' . $transaksi->kode_transaksi,
        ]);

        // ============================================
        // KIRIM NOTIFIKASI KE ADMIN & PETUGAS SAJA
        // ============================================
        $users = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Kendaraan Keluar',
                'message' => 'Kendaraan ' . $transaksi->kendaraan->plat_nomor . ' keluar - ' . $durasiHari . ' hari - Rp ' . number_format($totalTarif, 0, ',', '.'),
                'type' => 'info',
                'link' => route('transaksi.detail', $transaksi->id),
                'is_read' => false,
            ]);
        }

        // Update status area
        $totalKendaraan = Transaksi::where('area_id', $transaksi->area_id)->where('status', 'masuk')->count();
        $area = AreaParkir::find($transaksi->area_id);
        $persentase = ($totalKendaraan / $area->kapasitas) * 100;

        if ($persentase < 80) {
            $area->update(['status' => 'tersedia']);
        } elseif ($persentase < 100) {
            $area->update(['status' => 'hampir_penuh']);
        }

        return redirect()->route('transaksi.struk', $transaksi->id)
            ->with('success', 'Kendaraan berhasil keluar! Total bayar: Rp ' . number_format($totalTarif, 0, ',', '.'));
    }

    public function detail($id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas'])->findOrFail($id);
        return view('transaksi.detail', compact('transaksi'));
    }

    public function struk($id)
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas'])->findOrFail($id);
        return view('transaksi.struk', compact('transaksi'));
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasFeature('hapus_transaksi')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus transaksi.');
        }

        $transaksi = Transaksi::findOrFail($id);
        $kodeTransaksi = $transaksi->kode_transaksi;
        $transaksi->delete();

        LogAktivitas::create([
            'user_id' => auth()->user()->id,
            'aktivitas' => 'Menghapus transaksi: ' . $kodeTransaksi,
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi ' . $kodeTransaksi . ' berhasil dihapus.');
    }
}