<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return redirect()->back()->with('error', 'Masukkan kata kunci pencarian!');
        }

        // Cari di Kendaraan (plat_nomor, pemilik, merk, warna)
        $kendaraan = Kendaraan::where('plat_nomor', 'LIKE', "%{$query}%")
            ->orWhere('pemilik', 'LIKE', "%{$query}%")
            ->orWhere('merk', 'LIKE', "%{$query}%")
            ->orWhere('warna', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Cari di Transaksi (kode_transaksi, plat_nomor)
        $transaksi = Transaksi::with(['kendaraan'])
            ->where('kode_transaksi', 'LIKE', "%{$query}%")
            ->orWhereHas('kendaraan', function ($q) use ($query) {
                $q->where('plat_nomor', 'LIKE', "%{$query}%")
                    ->orWhere('merk', 'LIKE', "%{$query}%")
                    ->orWhere('pemilik', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get();

        // Cari di User (nama, username)
        $users = User::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return view('search.results', compact('query', 'kendaraan', 'transaksi', 'users'));
    }
}