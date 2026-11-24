<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk pencarian kendaraan
use App\Models\Transaksi; // Mengimpor model Transaksi untuk pencarian transaksi
use App\Models\User; // Mengimpor model User untuk pencarian pengguna

class SearchController extends Controller // Mendefinisikan controller pencarian global
{
    public function search(Request $request) // Method untuk melakukan pencarian lintas tabel
    {
        $query = $request->get('q'); // Mengambil kata kunci pencarian dari parameter 'q'

        if (empty($query)) { // Jika kata kunci kosong
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian!'); // Kembali dengan pesan error
        }

        // Cari di Kendaraan (plat_nomor, pemilik, merk, warna)
        $kendaraan = Kendaraan::where('plat_nomor', 'LIKE', "%{$query}%") // Cari plat nomor mengandung kata kunci
            ->orWhere('pemilik', 'LIKE', "%{$query}%") // Atau pemilik mengandung kata kunci
            ->orWhere('merk', 'LIKE', "%{$query}%") // Atau merk mengandung kata kunci
            ->orWhere('warna', 'LIKE', "%{$query}%") // Atau warna mengandung kata kunci
            ->limit(5) // Batasi 5 hasil
            ->get(); // Eksekusi query

        // Cari di Transaksi (kode_transaksi, plat_nomor)
        $transaksi = Transaksi::with(['kendaraan']) // Eager load relasi kendaraan
            ->where('kode_transaksi', 'LIKE', "%{$query}%") // Cari kode transaksi mengandung kata kunci
            ->orWhereHas('kendaraan', function ($q) use ($query) { // Atau cari di relasi kendaraan
                $q->where('plat_nomor', 'LIKE', "%{$query}%") // Plat nomor mengandung kata kunci
                    ->orWhere('merk', 'LIKE', "%{$query}%") // Atau merk mengandung kata kunci
                    ->orWhere('pemilik', 'LIKE', "%{$query}%"); // Atau pemilik mengandung kata kunci
            })
            ->limit(5) // Batasi 5 hasil
            ->get(); // Eksekusi query

        // Cari di User (nama, username)
        $users = User::where('nama', 'LIKE', "%{$query}%") // Cari nama mengandung kata kunci
            ->orWhere('username', 'LIKE', "%{$query}%") // Atau username mengandung kata kunci
            ->limit(5) // Batasi 5 hasil
            ->get(); // Eksekusi query

        return view('search.results', compact('query', 'kendaraan', 'transaksi', 'users')); // Kirim hasil ke view search.results
    }
}