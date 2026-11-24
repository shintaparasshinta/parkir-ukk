<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk operasi CRUD
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Log; // Mengimpor facade Log untuk debugging
use Illuminate\Database\QueryException; // Mengimpor exception query DB

class KendaraanController extends Controller // Mendefinisikan controller kendaraan
{
    public function index(Request $request) // Method untuk menampilkan daftar kendaraan (dengan pencarian)
    {
        $search = $request->q; // Mengambil parameter pencarian 'q'

        $query = Kendaraan::query(); // Query builder awal

        if ($search) { // Jika ada kata kunci
            $query->where(function ($q) use ($search) { // Kelompokkan kondisi OR
                $q->where('plat_nomor', 'like', "%{$search}%") // Cari plat nomor
                  ->orWhere('pemilik', 'like', "%{$search}%") // Atau pemilik
                  ->orWhere('merk', 'like', "%{$search}%"); // Atau merk
            });
        }

        $kendaraan = $query->orderBy('created_at', 'desc')->paginate(10); // Urutkan terbaru, paginasi

        return view('kendaraan.index', compact('kendaraan')); // Kirim ke view
    }

    public function create() // Method untuk menampilkan form tambah
    {
        return view('kendaraan.create'); // Tampilkan view create
    }

    public function store(Request $request) // Method untuk menyimpan kendaraan baru
    {
        try { // Coba proses
            $validated = $request->validate([ // Validasi input
                'plat_nomor'      => 'required|string|max:20|unique:kendaraan,plat_nomor', // Wajib, unik di tabel kendaraan
                'pemilik'         => 'required|string|max:255', // Wajib, string, max 255
                'jenis_kendaraan' => 'required|in:Motor,Mobil', // Wajib Motor/Mobil
                'merk'            => 'required|string|max:255', // Wajib, string, max 255
                'warna'           => 'required|string|max:100', // Wajib, string, max 100
            ]);

            Kendaraan::create($validated); // Simpan ke DB

            return redirect()->route('kendaraan.index') // Redirect ke index
                ->with('success', 'Data kendaraan berhasil ditambahkan.'); // Pesan sukses
        } catch (QueryException $e) { // Tangkap error query DB
            Log::error('Gagal tambah kendaraan (DB): ' . $e->getMessage()); // Catat ke log
            return redirect()->back() // Kembali ke form
                ->withInput() // Bawa input sebelumnya
                ->with('error', 'Gagal menyimpan kendaraan: ' . $e->getMessage()); // Pesan error
        }
    }

    public function show(string $id) // Method untuk menampilkan detail kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari berdasarkan ID
        return view('kendaraan.show', compact('kendaraan')); // Kirim ke view
    }

    public function edit(string $id) // Method untuk menampilkan form edit
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari berdasarkan ID
        return view('kendaraan.edit', compact('kendaraan')); // Kirim ke view
    }

    public function update(Request $request, string $id) // Method untuk memperbarui data kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan

        try { // Coba proses
            $validated = $request->validate([ // Validasi input update
                'plat_nomor'      => 'required|string|max:20|unique:kendaraan,plat_nomor,' . $kendaraan->id, // Unik kecuali ID ini
                'pemilik'         => 'required|string|max:255', // Wajib, string, max 255
                'jenis_kendaraan' => 'required|in:Motor,Mobil', // Wajib Motor/Mobil
                'merk'            => 'required|string|max:255', // Wajib, string, max 255
                'warna'           => 'required|string|max:100', // Wajib, string, max 100
            ]);

            $kendaraan->update($validated); // Update di DB

            return redirect()->route('kendaraan.index') // Redirect ke index
                ->with('success', 'Data kendaraan berhasil diperbarui.'); // Pesan sukses
        } catch (QueryException $e) { // Tangkap error query DB
            Log::error('Gagal update kendaraan (DB): ' . $e->getMessage()); // Catat ke log
            return redirect()->back() // Kembali ke form
                ->withInput() // Bawa input sebelumnya
                ->with('error', 'Gagal memperbarui kendaraan: ' . $e->getMessage()); // Pesan error
        }
    }

    public function destroy(string $id) // Method untuk menghapus kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan
        $kendaraan->delete(); // Hapus dari DB

        return redirect()->route('kendaraan.index') // Redirect ke index
            ->with('success', 'Data kendaraan berhasil dihapus.'); // Pesan sukses
    }
}