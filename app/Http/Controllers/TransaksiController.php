<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\Transaksi; // Mengimpor model Transaksi untuk data transaksi parkir
use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk data kendaraan
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk data & kapasitas area
use App\Models\TarifParkir; // Mengimpor model TarifParkir untuk tarif per hari
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk pencatatan aktivitas
use App\Models\Notification; // Mengimpor model Notification untuk notifikasi ke admin/petugas
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi manual
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal/waktu
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk update langsung

class TransaksiController extends Controller // Mendefinisikan controller transaksi parkir
{
    public function index() // Method untuk menampilkan daftar transaksi
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas']) // Eager load relasi untuk hindari N+1
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->paginate(10); // Paginasi 10 per halaman
        return view('transaksi.index', compact('transaksi')); // Kirim data ke view transaksi.index
    }

    /**
     * Halaman Kendaraan Masuk
     * - Filter: kendaraan yang sedang parkir (status = 'masuk') TIDAK muncul di dropdown
     * - Area parkir + sisa kapasitas
     */
    public function masuk() // Method untuk menampilkan form kendaraan masuk
    {
        // 1. Ambil ID kendaraan yang sedang parkir (status = 'masuk')
        $kendaraanParkir = Transaksi::where('status', 'masuk') // Filter transaksi yang masih berjalan
            ->pluck('kendaraan_id') // Ambil hanya kolom kendaraan_id
            ->toArray(); // Konversi collection ke array

        // 2. Hanya kendaraan yang TIDAK sedang parkir yang muncul di dropdown
        $kendaraan = Kendaraan::whereNotIn('id', $kendaraanParkir) // Kecualikan kendaraan yang sedang parkir
            ->orderBy('plat_nomor') // Urutkan berdasarkan plat nomor
            ->get(); // Eksekusi query

        // 3. Area parkir + sisa kapasitas
        $areaParkir = AreaParkir::where('status', '!=', 'penuh') // Hanya tampilkan area yang tidak penuh
            ->orderBy('nama_area') // Urutkan berdasarkan nama area
            ->get() // Eksekusi query
            ->map(function ($area) { // Transformasi tiap area untuk tambah info kapasitas
                $terisi = Transaksi::where('area_id', $area->id) // Hitung transaksi aktif di area ini
                    ->where('status', 'masuk')
                    ->count();
                $area->terisi = $terisi; // Tambahkan properti terisi
                $area->tersedia = max(0, $area->kapasitas - $terisi); // Hitung sisa kapasitas, tidak boleh negatif
                return $area; // Kembalikan area yang sudah dimodifikasi
            });

        return view('transaksi.masuk', compact('kendaraan', 'areaParkir')); // Kirim data ke view transaksi.masuk
    }

    public function prosesMasuk(Request $request) // Method untuk memproses kendaraan masuk
    {
        $validator = Validator::make($request->all(), [ // Validasi input proses masuk
            'kendaraan_id' => 'required|exists:kendaraan,id', // Kendaraan wajib dan harus ada
            'area_id' => 'required|exists:area_parkir,id', // Area wajib dan harus ada
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput(); // Kembali dengan error & input
        }

        // Cek apakah kendaraan sudah parkir (safety check kalau ada yang bypass dropdown)
        $cekTransaksi = Transaksi::where('kendaraan_id', $request->kendaraan_id) // Cari transaksi kendaraan ini
            ->where('status', 'masuk') // Yang masih aktif
            ->first(); // Ambil satu data pertama

        if ($cekTransaksi) { // Jika kendaraan sudah parkir
            return redirect()->back() // Kembali
                ->with('error', 'Kendaraan ini sudah parkir! Silahkan proses keluar terlebih dahulu.') // Pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        $area = AreaParkir::find($request->area_id); // Ambil data area parkir
        $terisi = Transaksi::where('area_id', $area->id)->where('status', 'masuk')->count(); // Hitung jumlah terisi saat ini
        if ($terisi >= $area->kapasitas) { // Jika kapasitas sudah penuh (validasi ulang di server)
            return redirect()->back() // Kembali
                ->with('error', 'Area ' . $area->nama_area . ' sudah penuh! Pilih area lain.') // Pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        // Generate kode transaksi
        $kodeTransaksi = 'TRX-' . date('Ymd') . '-' . str_pad(Transaksi::count() + 1, 3, '0', STR_PAD_LEFT); // Format: TRX-YYYYMMDD-NNN
        $kendaraan = Kendaraan::find($request->kendaraan_id); // Ambil data kendaraan

        // Ambil tarif PER HARI berdasarkan area & jenis kendaraan
        $tarif = TarifParkir::where('area_id', $request->area_id) // Filter berdasarkan area
            ->where('jenis_kendaraan', $kendaraan->jenis_kendaraan) // Dan jenis kendaraan
            ->first(); // Ambil satu tarif

        if (!$tarif) { // Jika tarif belum diatur
            return redirect()->back() // Kembali
                ->with('error', 'Tarif untuk area ini dan jenis kendaraan belum diatur!') // Pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        // Buat transaksi (kolom tarif = tarif per hari)
        $transaksi = Transaksi::create([ // Simpan transaksi baru
            'kode_transaksi' => $kodeTransaksi, // Kode unik transaksi
            'kendaraan_id' => $request->kendaraan_id, // ID kendaraan
            'area_id' => $request->area_id, // ID area parkir
            'petugas_id' => auth()->user()->id, // ID petugas yang memproses
            'waktu_masuk' => Carbon::now(), // Waktu masuk sekarang
            'waktu_keluar' => null, // Belum keluar
            'tarif' => $tarif->tarif, // Tarif per hari (bukan total)
            'status' => 'masuk', // Status awal: masuk
        ]);

        LogAktivitas::create([ // Catat log kendaraan masuk
            'user_id' => auth()->user()->id, // ID petugas
            'aktivitas' => 'Kendaraan masuk: ' . $kendaraan->plat_nomor . ' (' . $kendaraan->jenis_kendaraan . ') - Area: ' . $transaksi->areaParkir->nama_area . ' - Kode: ' . $kodeTransaksi, // Deskripsi aktivitas
        ]);

        // ============================================
        // KIRIM NOTIFIKASI KE ADMIN & PETUGAS SAJA
        // ============================================
        $users = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get(); // Ambil semua user admin & petugas
        foreach ($users as $user) { // Iterasi tiap user
            Notification::create([ // Buat notifikasi untuk user tersebut
                'user_id' => $user->id, // Penerima notifikasi
                'title' => 'Kendaraan Masuk', // Judul notifikasi
                'message' => 'Kendaraan ' . $kendaraan->plat_nomor . ' (' . $kendaraan->jenis_kendaraan . ') masuk ke ' . $transaksi->areaParkir->nama_area, // Isi pesan
                'type' => 'success', // Tipe notifikasi
                'link' => route('transaksi.detail', $transaksi->id), // Link ke detail transaksi
                'is_read' => false, // Belum dibaca
            ]);
        }

        // Update status area
        $totalKendaraan = Transaksi::where('area_id', $request->area_id)->where('status', 'masuk')->count(); // Hitung total terisi setelah masuk
        $area = AreaParkir::find($request->area_id); // Ambil ulang data area
        $persentase = ($totalKendaraan / $area->kapasitas) * 100; // Hitung persentase keterisian

        if ($persentase >= 100) { // Jika sudah 100% atau lebih
            $area->update(['status' => 'penuh']); // Set status penuh
        } elseif ($persentase >= 80) { // Jika 80% ke atas
            $area->update(['status' => 'hampir_penuh']); // Set status hampir penuh
        }

        return redirect()->route('transaksi.struk', $transaksi->id) // Redirect ke halaman struk
            ->with('success', 'Kendaraan berhasil masuk! Kode transaksi: ' . $kodeTransaksi); // Pesan sukses
    }

    public function keluar() // Method untuk menampilkan form kendaraan keluar
    {
        $transaksiMasuk = Transaksi::with(['kendaraan', 'areaParkir']) // Eager load relasi
            ->where('status', 'masuk') // Hanya transaksi yang masih aktif
            ->orderBy('waktu_masuk') // Urutkan dari yang paling lama masuk
            ->get(); // Eksekusi query
        return view('transaksi.keluar', compact('transaksiMasuk')); // Kirim data ke view transaksi.keluar
    }

    public function prosesKeluar(Request $request) // Method untuk memproses kendaraan keluar
    {
        $validator = Validator::make($request->all(), [ // Validasi input proses keluar
            'transaksi_id' => 'required|exists:transaksi,id', // Transaksi wajib dan harus ada
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput(); // Kembali dengan error & input
        }

        $transaksi = Transaksi::with(['kendaraan', 'areaParkir'])->find($request->transaksi_id); // Ambil transaksi dengan relasi

        if (!$transaksi || $transaksi->status == 'keluar') { // Jika transaksi tidak ada atau sudah keluar
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan atau sudah selesai!'); // Pesan error
        }

        // ============================================
        // HITUNG DURASI PARKIR PER HARI
        // ============================================
        $waktuMasuk = Carbon::parse($transaksi->waktu_masuk)->startOfDay(); // Waktu masuk di-normalisasi ke awal hari
        $waktuKeluar = Carbon::now()->startOfDay(); // Waktu keluar di-normalisasi ke awal hari

        $durasiHari = $waktuMasuk->diffInDays($waktuKeluar) + 1; // Hitung selisih hari + 1 (hari masuk dihitung)
        if ($durasiHari < 1) $durasiHari = 1; // Pastikan minimal 1 hari (safety check)

        $tarifPerHari = $transaksi->tarif; // Ambil tarif per hari yang tersimpan di transaksi
        $totalTarif = $durasiHari * $tarifPerHari; // Total = durasi hari × tarif per hari

        // UPDATE transaksi
        DB::table('transaksi') // Menggunakan query builder langsung (tanpa model)
            ->where('id', $transaksi->id) // Filter berdasarkan ID transaksi
            ->update([ // Update beberapa kolom sekaligus
                'waktu_keluar' => Carbon::now(), // Set waktu keluar sekarang
                'tarif' => $totalTarif, // Set tarif = total biaya akhir
                'status' => 'keluar', // Ubah status menjadi keluar
                'updated_at' => now(), // Update timestamp
            ]);

        $transaksi->refresh(); // Refresh model agar data terbaru dari DB

        LogAktivitas::create([ // Catat log kendaraan keluar
            'user_id' => auth()->user()->id, // ID petugas
            'aktivitas' => 'Kendaraan keluar: ' . $transaksi->kendaraan->plat_nomor . ' - Durasi: ' . $durasiHari . ' hari - Total bayar Rp ' . number_format($totalTarif, 0, ',', '.') . ' - Kode: ' . $transaksi->kode_transaksi, // Deskripsi aktivitas
        ]);

        // ============================================
        // KIRIM NOTIFIKASI KE ADMIN & PETUGAS SAJA
        // ============================================
        $users = \App\Models\User::whereIn('role', ['admin', 'petugas'])->get(); // Ambil semua user admin & petugas
        foreach ($users as $user) { // Iterasi tiap user
            Notification::create([ // Buat notifikasi untuk user tersebut
                'user_id' => $user->id, // Penerima notifikasi
                'title' => 'Kendaraan Keluar', // Judul notifikasi
                'message' => 'Kendaraan ' . $transaksi->kendaraan->plat_nomor . ' keluar - ' . $durasiHari . ' hari - Rp ' . number_format($totalTarif, 0, ',', '.'), // Isi pesan
                'type' => 'info', // Tipe notifikasi
                'link' => route('transaksi.detail', $transaksi->id), // Link ke detail transaksi
                'is_read' => false, // Belum dibaca
            ]);
        }

        // Update status area
        $totalKendaraan = Transaksi::where('area_id', $transaksi->area_id)->where('status', 'masuk')->count(); // Hitung total terisi setelah keluar
        $area = AreaParkir::find($transaksi->area_id); // Ambil data area
        $persentase = ($totalKendaraan / $area->kapasitas) * 100; // Hitung persentase keterisian

        if ($persentase < 80) { // Jika di bawah 80%
            $area->update(['status' => 'tersedia']); // Set status tersedia
        } elseif ($persentase < 100) { // Jika 80% - 99%
            $area->update(['status' => 'hampir_penuh']); // Set status hampir penuh
        }

        return redirect()->route('transaksi.struk', $transaksi->id) // Redirect ke halaman struk
            ->with('success', 'Kendaraan berhasil keluar! Total bayar: Rp ' . number_format($totalTarif, 0, ',', '.')); // Pesan sukses
    }

    public function detail($id) // Method untuk menampilkan detail transaksi
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas'])->findOrFail($id); // Cari transaksi + relasi, error 404 jika tidak ada
        return view('transaksi.detail', compact('transaksi')); // Kirim data ke view transaksi.detail
    }

    public function struk($id) // Method untuk menampilkan struk transaksi
    {
        $transaksi = Transaksi::with(['kendaraan', 'areaParkir', 'petugas'])->findOrFail($id); // Cari transaksi + relasi
        return view('transaksi.struk', compact('transaksi')); // Kirim data ke view transaksi.struk
    }

    public function destroy($id) // Method untuk menghapus transaksi
    {
        if (!auth()->user()->hasFeature('hapus_transaksi')) { // Cek fitur hapus_transaksi pada user
            abort(403, 'Anda tidak memiliki izin untuk menghapus transaksi.'); // Tolak dengan HTTP 403 jika tidak berhak
        }

        $transaksi = Transaksi::findOrFail($id); // Cari transaksi berdasarkan ID
        $kodeTransaksi = $transaksi->kode_transaksi; // Simpan kode transaksi untuk log
        $transaksi->delete(); // Hapus transaksi dari database

        LogAktivitas::create([ // Catat log penghapusan transaksi
            'user_id' => auth()->user()->id, // ID user yang melakukan
            'aktivitas' => 'Menghapus transaksi: ' . $kodeTransaksi, // Deskripsi aktivitas
        ]);

        return redirect()->route('transaksi.index') // Redirect ke halaman index transaksi
            ->with('success', 'Transaksi ' . $kodeTransaksi . ' berhasil dihapus.'); // Pesan sukses
    }
}