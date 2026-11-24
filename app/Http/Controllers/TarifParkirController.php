<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\TarifParkir; // Mengimpor model TarifParkir untuk operasi CRUD tarif
use App\Models\AreaParkir; // Mengimpor model AreaParkir untuk relasi & validasi area
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk pencatatan aktivitas
use App\Models\Notification; // Mengimpor model Notification untuk notifikasi ke owner
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi manual
use Illuminate\Validation\Rule; // Mengimpor Rule untuk validasi unique gabungan

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
        // Validasi unique gabungan (area_id + jenis_kendaraan) agar tidak ada duplikasi tarif
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input tarif
            'area_id' => [ // Aturan untuk area_id
                'required', // Wajib diisi
                'exists:area_parkir,id', // Harus ada di tabel area_parkir
                Rule::unique('tarif_parkir')->where(function ($query) use ($request) { // Cek unik dengan kondisi tambahan
                    return $query->where('jenis_kendaraan', $request->jenis_kendaraan); // Kombinasi area + jenis kendaraan harus unik
                })
            ],
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Jenis kendaraan wajib Motor atau Mobil
            'tarif' => 'required|numeric|min:0', // Tarif wajib, numerik, tidak boleh negatif
        ], [
            'area_id.unique' => 'Tarif untuk jenis kendaraan ini di area tersebut sudah ada!' // Pesan error custom untuk unique
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput(); // Kembali dengan error & input sebelumnya
        }

        $area = AreaParkir::find($request->area_id); // Ambil data area untuk kebutuhan log
        TarifParkir::create($request->all()); // Simpan tarif baru ke database

        LogAktivitas::create([ // Mencatat log penambahan tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Menambahkan tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($request->tarif, 0, ',', '.'), // Deskripsi aktivitas dengan format rupiah
        ]);

        Notification::kirimKeOwner( // Kirim notifikasi ke owner
            'Tarif Baru Ditambahkan', // Judul notifikasi
            'Admin menambahkan tarif ' . $request->jenis_kendaraan . ' di ' . $area->nama_area . ' sebesar Rp ' . number_format($request->tarif, 0, ',', '.'), // Isi pesan
            'warning', // Tipe notifikasi
            route('tarif.index') // Link ke halaman tarif
        );

        return redirect()->route('tarif.index')->with('success', 'Tarif parkir berhasil ditambahkan!'); // Redirect dengan pesan sukses
    }

    public function edit(TarifParkir $tarif) // Method untuk menampilkan form edit tarif (route model binding)
    {
        $areas = AreaParkir::all(); // Ambil semua area parkir untuk dropdown pilihan
        return view('tarif.edit', compact('tarif', 'areas')); // Kirim data tarif dan area ke view tarif.edit
    }

    public function update(Request $request, TarifParkir $tarif) // Method untuk memperbarui tarif parkir
    {
        // Validasi unique gabungan, abaikan ID tarif yang sedang diedit
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input update
            'area_id' => [ // Aturan untuk area_id
                'required', // Wajib diisi
                'exists:area_parkir,id', // Harus ada di tabel area_parkir
                Rule::unique('tarif_parkir')->where(function ($query) use ($request) { // Cek unik area + jenis
                    return $query->where('jenis_kendaraan', $request->jenis_kendaraan);
                })->ignore($tarif->id) // Abaikan record yang sedang diedit
            ],
            'jenis_kendaraan' => 'required|in:Motor,Mobil', // Jenis kendaraan wajib Motor atau Mobil
            'tarif' => 'required|numeric|min:0', // Tarif wajib, numerik, tidak negatif
        ], [
            'area_id.unique' => 'Tarif untuk jenis kendaraan ini di area tersebut sudah ada!' // Pesan error custom
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back()->withErrors($validator)->withInput(); // Kembali dengan error & input sebelumnya
        }

        $oldTarif = $tarif->tarif; // Simpan tarif lama untuk log perubahan
        $area = AreaParkir::find($request->area_id); // Ambil data area untuk log
        $tarif->update($request->all()); // Update data tarif di database

        LogAktivitas::create([ // Mencatat log update tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Mengupdate tarif parkir: ' . $area->nama_area . ' - ' . $request->jenis_kendaraan . ' Rp ' . number_format($oldTarif, 0, ',', '.') . ' -> Rp ' . number_format($request->tarif, 0, ',', '.'), // Deskripsi perubahan tarif lama -> baru
        ]);

        Notification::kirimKeOwner( // Kirim notifikasi ke owner
            'Perubahan Tarif Parkir', // Judul notifikasi
            'Admin mengubah tarif ' . $request->jenis_kendaraan . ' di ' . $area->nama_area . ' dari Rp ' . number_format($oldTarif, 0, ',', '.') . ' menjadi Rp ' . number_format($request->tarif, 0, ',', '.'), // Isi pesan
            'warning', // Tipe notifikasi
            route('tarif.index') // Link ke halaman tarif
        );

        return redirect()->route('tarif.index')->with('success', 'Tarif parkir berhasil diperbarui!'); // Redirect dengan pesan sukses
    }

    public function destroy(TarifParkir $tarif) // Method untuk menghapus tarif parkir
    {
        $area = AreaParkir::find($tarif->area_id); // Ambil data area terkait untuk log
        $jenis = $tarif->jenis_kendaraan; // Simpan jenis kendaraan sebelum dihapus

        LogAktivitas::create([ // Mencatat log penghapusan tarif
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Menghapus tarif parkir: ' . $area->nama_area . ' - ' . $jenis, // Deskripsi aktivitas hapus
        ]);

        Notification::kirimKeOwner( // Kirim notifikasi ke owner
            'Tarif Parkir Dihapus', // Judul notifikasi
            'Admin menghapus tarif ' . $jenis . ' di ' . $area->nama_area, // Isi pesan
            'danger', // Tipe notifikasi
            route('tarif.index') // Link ke halaman tarif
        );

        $tarif->delete(); // Hapus data tarif dari database

        return redirect()->route('tarif.index')->with('success', 'Tarif parkir berhasil dihapus!'); // Redirect dengan pesan sukses
    }
}