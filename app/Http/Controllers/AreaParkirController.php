<?php

namespace App\Http\Controllers; // Menentukan namespace controller agar sesuai struktur folder App\Http\Controllers

use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk operasi CRUD data area parkir
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk mencatat aktivitas pengguna
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi input manual

class AreaParkirController extends Controller // Mendefinisikan controller yang mewarisi base Controller
{
    public function index() // Method untuk menampilkan daftar area parkir
    {
        $areaParkir = AreaParkir::orderBy('created_at', 'desc')->paginate(10); // Mengambil data area parkir diurutkan terbaru, 10 per halaman
        return view('area.index', compact('areaParkir')); // Mengirim data ke view area.index
    }

    public function create() // Method untuk menampilkan form tambah area parkir
    {
        return view('area.create'); // Menampilkan view form create
    }

    public function store(Request $request) // Method untuk menyimpan area parkir baru
    {
        $validator = Validator::make($request->all(), [ // Membuat validator dengan aturan validasi
            'nama_area' => 'required|string|max:100', // Nama area wajib, string, maksimal 100 karakter
            'kapasitas' => 'required|integer|min:1', // Kapasitas wajib, bilangan bulat, minimal 1
            'jenis_kendaraan' => 'required|in:semua,Motor,Mobil', // Jenis kendaraan wajib salah satu dari nilai yang ditentukan
            'status' => 'required|in:tersedia,penuh,hampir_penuh', // Status wajib salah satu dari nilai yang ditentukan
        ]);

        if ($validator->fails()) { // Mengecek apakah validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Membawa pesan error validasi
                ->withInput(); // Mengembalikan input sebelumnya agar tidak hilang
        }

        $area = AreaParkir::create($request->all()); // Menyimpan data area parkir baru ke database

        LogAktivitas::create([ // Mencatat log aktivitas penambahan area
            'user_id' => auth()->user()->id, // ID pengguna yang sedang login
            'aktivitas' => 'Menambahkan area parkir: ' . $request->nama_area . ' (Kapasitas: ' . $request->kapasitas . ', Jenis: ' . $request->jenis_kendaraan . ')', // Deskripsi aktivitas
        ]);

        return redirect()->route('area.index') // Redirect ke halaman index area parkir
            ->with('success', 'Area parkir berhasil ditambahkan!'); // Membawa pesan sukses
    }

    public function edit(AreaParkir $area) // Method untuk menampilkan form edit, dengan route model binding
    {
        return view('area.edit', compact('area')); // Mengirim data area ke view edit
    }

    public function update(Request $request, AreaParkir $area) // Method untuk memperbarui data area parkir
    {
        $validator = Validator::make($request->all(), [ // Membuat validator dengan aturan validasi
            'nama_area' => 'required|string|max:100', // Nama area wajib, string, maksimal 100 karakter
            'kapasitas' => 'required|integer|min:1', // Kapasitas wajib, bilangan bulat, minimal 1
            'jenis_kendaraan' => 'required|in:semua,Motor,Mobil', // Jenis kendaraan wajib salah satu dari nilai yang ditentukan
            'status' => 'required|in:tersedia,penuh,hampir_penuh', // Status wajib salah satu dari nilai yang ditentukan
        ]);

        if ($validator->fails()) { // Mengecek apakah validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Membawa pesan error validasi
                ->withInput(); // Mengembalikan input sebelumnya
        }

        $oldName = $area->nama_area; // Menyimpan nama area lama untuk keperluan log
        $area->update($request->all()); // Memperbarui data area parkir di database

        LogAktivitas::create([ // Mencatat log aktivitas update area
            'user_id' => auth()->user()->id, // ID pengguna yang sedang login
            'aktivitas' => 'Mengupdate area parkir: ' . $oldName . ' -> ' . $request->nama_area, // Deskripsi perubahan nama area
        ]);

        return redirect()->route('area.index') // Redirect ke halaman index area parkir
            ->with('success', 'Area parkir berhasil diperbarui!'); // Membawa pesan sukses
    }

    public function destroy(AreaParkir $area) // Method untuk menghapus area parkir
    {
        $nama = $area->nama_area; // Menyimpan nama area sebelum dihapus untuk log

        LogAktivitas::create([ // Mencatat log aktivitas penghapusan area
            'user_id' => auth()->user()->id, // ID pengguna yang sedang login
            'aktivitas' => 'Menghapus area parkir: ' . $nama, // Deskripsi aktivitas hapus
        ]);

        $area->delete(); // Menghapus data area parkir dari database

        return redirect()->route('area.index') // Redirect ke halaman index area parkir
            ->with('success', 'Area parkir berhasil dihapus!'); // Membawa pesan sukses
    }
}