<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    /**
     * Tampilkan daftar kendaraan.
     */
    public function index(Request $request)
    {
        $search = $request->q;

        $query = Kendaraan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                  ->orWhere('pemilik', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%");
            });
        }

        $kendaraan = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('kendaraan.index', compact('kendaraan'));
    }

    /**
     * Tampilkan form tambah kendaraan.
     */
    public function create()
    {
        return view('kendaraan.create');
    }

    /**
     * Simpan kendaraan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plat_nomor'      => 'required|string|max:20|unique:kendaraans,plat_nomor',
            'pemilik'         => 'required|string|max:255',
            'jenis_kendaraan' => 'required|in:Motor,Mobil',
            'merk'            => 'required|string|max:255',
            'warna'           => 'required|string|max:100',
        ]);

        Kendaraan::create($validated);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail kendaraan (tidak dipakai di UI saat ini, disediakan untuk resource route).
     */
    public function show(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        return view('kendaraan.show', compact('kendaraan'));
    }

    /**
     * Tampilkan form edit kendaraan.
     */
    public function edit(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        return view('kendaraan.edit', compact('kendaraan'));
    }

    /**
     * Update data kendaraan.
     */
    public function update(Request $request, string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $validated = $request->validate([
            'plat_nomor'      => 'required|string|max:20|unique:kendaraans,plat_nomor,' . $kendaraan->id,
            'pemilik'         => 'required|string|max:255',
            'jenis_kendaraan' => 'required|in:Motor,Mobil',
            'merk'            => 'required|string|max:255',
            'warna'           => 'required|string|max:100',
        ]);

        $kendaraan->update($validated);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    /**
     * Hapus kendaraan.
     */
    public function destroy(string $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil dihapus.');
    }
}