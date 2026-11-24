<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\Kendaraan; // Mengimpor model Kendaraan untuk operasi CRUD
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP

class KendaraanController extends Controller // Mendefinisikan controller kendaraan
{
    /**
     * Tampilkan daftar kendaraan.
     */
    public function index(Request $request) // Method untuk menampilkan daftar kendaraan (dengan pencarian)
    {
        $search = $request->q; // Mengambil parameter pencarian 'q' dari query string

        $query = Kendaraan::query(); // Membuat query builder awal untuk model Kendaraan

        if ($search) { // Jika ada kata kunci pencarian
            $query->where(function ($q) use ($search) { // Mengelompokkan kondisi OR dalam satu grup
                $q->where('plat_nomor', 'like', "%{$search}%") // Cari plat nomor yang mengandung kata kunci
                  ->orWhere('pemilik', 'like', "%{$search}%") // Atau pemilik mengandung kata kunci
                  ->orWhere('merk', 'like', "%{$search}%"); // Atau merk mengandung kata kunci
            });
        }

        $kendaraan = $query->orderBy('created_at', 'desc')->paginate(10); // Urutkan terbaru, paginasi 10 per halaman

        return view('kendaraan.index', compact('kendaraan')); // Kirim data ke view kendaraan.index
    }

    /**
     * Tampilkan form tambah kendaraan.
     */
    public function create() // Method untuk menampilkan form tambah kendaraan
    {
        return view('kendaraan.create'); // Menampilkan view form create
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request) // Method untuk menyimpan kendaraan baru
    {
        $validated = $request->validate([ // Validasi input dan ambil data yang lolos validasi
            'plat_nomor'      => 'required|string|max:20|unique:kendaraan,plat_nomor', // Wajib, string, max 20, unik di tabel kendaraan
            'pemilik'         => 'required|string|max:255', // Wajib, string, max 255
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Wajib, hanya Motor atau Mobil
            'merk'            => 'required|string|max:255', // Wajib, string, max 255
            'warna'           => 'required|string|max:100', // Wajib, string, max 100
        ]);

        Kendaraan::create($validated); // Menyimpan data kendaraan baru ke database

        return redirect()->route('kendaraan.index') // Redirect ke daftar kendaraan
            ->with('success', 'Data kendaraan berhasil ditambahkan.'); // Pesan sukses
    }

    /**
     * Tampilkan detail kendaraan (tidak dipakai di UI saat ini, disediakan untuk resource route).
     */
    public function show(string $id) // Method untuk menampilkan detail kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan berdasarkan ID, error 404 jika tidak ada

        return view('kendaraan.show', compact('kendaraan')); // Kirim data ke view kendaraan.show
    }

    /**
     * Tampilkan form edit kendaraan.
     */
    public function edit(string $id) // Method untuk menampilkan form edit
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan berdasarkan ID

        return view('kendaraan.edit', compact('kendaraan')); // Kirim data ke view kendaraan.edit
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, string $id) // Method untuk memperbarui data kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan yang akan diupdate

        $validated = $request->validate([ // Validasi input update
            'plat_nomor'      => 'required|string|max:20|unique:kendaraan,plat_nomor,' . $kendaraan->id, // Unik di tabel kendaraan kecuali untuk ID ini sendiri
            'pemilik'         => 'required|string|max:255', // Wajib, string, max 255
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Wajib, hanya Motor atau Mobil
            'merk'            => 'required|string|max:255', // Wajib, string, max 255
            'warna'           => 'required|string|max:100', // Wajib, string, max 100
        ]);

        $kendaraan->update($validated); // Memperbarui data kendaraan di database

        return redirect()->route('kendaraan.index') // Redirect ke daftar kendaraan
            ->with('success', 'Data kendaraan berhasil diperbarui.'); // Pesan sukses
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy(string $id) // Method untuk menghapus kendaraan
    {
        $kendaraan = Kendaraan::findOrFail($id); // Cari kendaraan berdasarkan ID
        $kendaraan->delete(); // Hapus data kendaraan dari database

        return redirect()->route('kendaraan.index') // Redirect ke daftar kendaraan
            ->with('success', 'Data kendaraan berhasil dihapus.'); // Pesan sukses
    }
}