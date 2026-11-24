<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\TarifParkir; // Mengimpor model TarifParkir untuk operasi CRUD tarif
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk relasi & validasi area
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk pencatatan aktivitas
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi manual

class TarifParkirController extends Controller // Mendefinisikan controller tarif parkir
{
    public function index() // Method untuk menampilkan daftar tarif parkir
    {
        $tarifParkir = TarifParkir::with('areaParkir') // Eager load relasi areaParkir untuk hindari N+1
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->paginate(10); // Paginasi 10 per halaman
        return view('tarif.index', compact('tarifParkir')); // Kirim data ke view tarif.index
    }

    public function create() // Method untuk menampilkan form tambah tarif
    {
        $areas = AreaParkir::all(); // Ambil semua area parkir untuk dropdown pilihan
        return view('tarif.create', compact('areas')); // Kirim data area ke view tarif.create
    }

    public function store(Request $request) // Method untuk menyimpan tarif parkir baru
    {
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input tarif
            'area_id' => 'required|exists:area_parkir,id', // Area wajib dan harus ada di tabel area_parkir
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Jenis kendaraan wajib Motor atau Mobil
            'tarif' => 'required|numeric|min:0', // Tarif wajib, numerik, tidak boleh negatif
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Bawa pesan error
                ->withInput(); // Bawa input sebelumnya agar tidak hilang
        }

        $area = AreaParkir::find($request->area_id); // Ambil data area untuk kebutuhan log
        TarifParkir::create($request->all()); // Simpan tarif baru ke database (perhatikan mass assignment)

        // Catat log
        LogAktivitas::create([ // Mencatat log penambahan tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Menambahkan tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($request->tarif, 0, ',', '.'), // Deskripsi aktivitas dengan format rupiah
        ]);

        return redirect()->route('tarif.index') // Redirect ke halaman index tarif
            ->with('success', 'Tarif parkir berhasil ditambahkan!'); // Pesan sukses
    }

    public function edit(TarifParkir $tarif) // Method untuk menampilkan form edit tarif (route model binding)
    {
        $areas = AreaParkir::all(); // Ambil semua area parkir untuk dropdown pilihan
        return view('tarif.edit', compact('tarif', 'areas')); // Kirim data tarif dan area ke view tarif.edit
    }

    public function update(Request $request, TarifParkir $tarif) // Method untuk memperbarui tarif parkir
    {
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input update
            'area_id' => 'required|exists:area_parkir,id', // Area wajib dan harus ada
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Jenis kendaraan wajib Motor atau Mobil
            'tarif' => 'required|numeric|min:0', // Tarif wajib, numerik, tidak negatif
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Bawa pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        $oldTarif = $tarif->tarif; // Simpan tarif lama untuk log perubahan
        $area = AreaParkir::find($request->area_id); // Ambil data area untuk log
        $tarif->update($request->all()); // Update data tarif di database

        // Catat log
        LogAktivitas::create([ // Mencatat log update tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Mengupdate tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($oldTarif, 0, ',', '.') . ' -> Rp ' . number_format($request->tarif, 0, ',', '.'), // Deskripsi perubahan tarif lama -> baru
        ]);

        return redirect()->route('tarif.index') // Redirect ke halaman index tarif
            ->with('success', 'Tarif parkir berhasil diperbarui!'); // Pesan sukses
    }

    public function destroy(TarifParkir $tarif) // Method untuk menghapus tarif parkir
    {
        $area = AreaParkir::find($tarif->area_id); // Ambil data area terkait untuk log
        $jenis = $tarif->jenis_kendaraan; // Simpan jenis kendaraan sebelum dihapus

        // Catat log
        LogAktivitas::create([ // Mencatat log penghapusan tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Menghapus tarif parkir: ' . $area->nama_area . ' - ' . $jenis, // Deskripsi aktivitas hapus
        ]);

        $tarif->delete(); // Hapus data tarif dari database

        return redirect()->route('tarif.index') // Redirect ke halaman index tarif
            ->with('success', 'Tarif parkir berhasil dihapus!'); // Pesan sukses
    }
}