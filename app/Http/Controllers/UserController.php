<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use App\Models\User; // Mengimpor model User untuk operasi CRUD pengguna
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk pencatatan aktivitas
use App\Models\Notification; // Mengimpor model Notification untuk notifikasi ke owner
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk hashing password
use Illuminate\Support\Facades\Validator; // Mengimpor facade Validator untuk validasi manual

class UserController extends Controller // Mendefinisikan controller manajemen pengguna
{
    public function index() // Method untuk menampilkan daftar pengguna
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10); // Ambil data user terbaru, paginasi 10 per halaman
        
        $totalUsers = User::count(); // Hitung total seluruh user
        $totalAdmin = User::where('role', 'admin')->count(); // Hitung total user dengan role admin
        $totalPetugas = User::where('role', 'petugas')->count(); // Hitung total user dengan role petugas
        $totalOwner = User::where('role', 'owner')->count(); // Hitung total user dengan role owner

        return view('pengguna.index', compact('users', 'totalUsers', 'totalAdmin', 'totalPetugas', 'totalOwner')); // Kirim data ke view pengguna.index
    }

    public function create() // Method untuk menampilkan form tambah pengguna
    {
        return view('pengguna.create'); // Menampilkan view form create
    }

    public function store(Request $request) // Method untuk menyimpan pengguna baru
    {
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input pengguna baru
            'nama' => 'required|string|max:100', // Nama wajib, string, max 100
            'username' => 'required|string|max:50|unique:users', // Username wajib, unik di tabel users
            'password' => 'required|string|min:6', // Password wajib, minimal 6 karakter
            'role' => 'required|in:admin,petugas,owner', // Role wajib salah satu dari tiga opsi
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Bawa pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        $user = User::create([ // Simpan user baru ke database
            'nama' => $request->nama, // Nama lengkap
            'username' => $request->username, // Username unik
            'password' => Hash::make($request->password), // Password di-hash sebelum disimpan
            'role' => $request->role, // Role pengguna (kolom legacy)
        ]);

        // [PERBAIKAN KRITIS] Sinkronisasi role ke Spatie Permission
        $user->assignRole($request->role); // Assign role Spatie agar hasFeature() berfungsi

        // Catat log
        LogAktivitas::create([ // Mencatat log penambahan pengguna
            'user_id' => auth()->user()->id, // ID admin yang menambah
            'aktivitas' => 'Menambahkan pengguna: ' . $request->username . ' (' . $request->role . ')', // Deskripsi aktivitas
        ]);

        // Kirim notif ke Owner
        Notification::kirimKeOwner( // Kirim notifikasi ke semua owner
            'Pengguna Baru Ditambahkan', // Judul notifikasi
            'Admin menambahkan pengguna baru: ' . $request->nama . ' dengan hak akses ' . strtoupper($request->role), // Isi pesan
            'info', // Tipe notifikasi
            route('pengguna.index') // Link ke halaman pengguna
        );

        return redirect()->route('pengguna.index') // Redirect ke halaman index pengguna
            ->with('success', 'Pengguna berhasil ditambahkan!'); // Pesan sukses
    }

    public function edit(User $pengguna) // Method untuk menampilkan form edit pengguna (route model binding)
    {
        return view('pengguna.edit', compact('pengguna')); // Kirim data user ke view pengguna.edit
    }

    public function update(Request $request, User $pengguna) // Method untuk memperbarui data pengguna
    {
        $validator = Validator::make($request->all(), [ // Membuat validator untuk input update
            'nama' => 'required|string|max:100', // Nama wajib, string, max 100
            'username' => 'required|string|max:50|unique:users,username,' . $pengguna->id, // Username unik kecuali milik user ini
            'role' => 'required|in:admin,petugas,owner', // Role wajib salah satu dari tiga opsi
            'password' => 'nullable|string|min:6', // Password opsional, minimal 6 karakter jika diisi
        ]);

        if ($validator->fails()) { // Jika validasi gagal
            return redirect()->back() // Kembali ke halaman sebelumnya
                ->withErrors($validator) // Bawa pesan error
                ->withInput(); // Bawa input sebelumnya
        }

        $data = [ // Siapkan data yang akan diupdate
            'nama' => $request->nama, // Nama baru
            'username' => $request->username, // Username baru
            'role' => $request->role, // Role baru
        ];

        if ($request->filled('password')) { // Jika password diisi (opsional)
            $data['password'] = Hash::make($request->password); // Hash dan tambahkan ke data
        }

        $pengguna->update($data); // Update data user di database

        // [PERBAIKAN KRITIS] Sinkronisasi role Spatie — ganti role lama dengan yang baru
        $pengguna->syncRoles([$request->role]); // syncRoles mencabut role lama & assign role baru

        // Catat log
        LogAktivitas::create([ // Mencatat log update pengguna
            'user_id' => auth()->user()->id, // ID admin yang mengupdate
            'aktivitas' => 'Mengupdate pengguna: ' . $request->username, // Deskripsi aktivitas
        ]);

        // Kirim notif ke Owner
        Notification::kirimKeOwner( // Kirim notifikasi ke semua owner
            'Perubahan Hak Akses Pengguna', // Judul notifikasi
            'Admin memperbarui data pengguna: ' . $request->nama . ' menjadi ' . strtoupper($request->role), // Isi pesan
            'warning', // Tipe notifikasi
            route('pengguna.index') // Link ke halaman pengguna
        );

        return redirect()->route('pengguna.index') // Redirect ke halaman index pengguna
            ->with('success', 'Pengguna berhasil diperbarui!'); // Pesan sukses
    }

    public function destroy(User $pengguna) // Method untuk menghapus pengguna
    {
        if ($pengguna->id == auth()->user()->id) { // Cegah user menghapus akun sendiri
            return redirect()->route('pengguna.index') // Redirect ke halaman index
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!'); // Pesan error
        }

        $username = $pengguna->username; // Simpan username sebelum dihapus untuk log

        // Catat log
        LogAktivitas::create([ // Mencatat log penghapusan pengguna
            'user_id' => auth()->user()->id, // ID admin yang menghapus
            'aktivitas' => 'Menghapus pengguna: ' . $username, // Deskripsi aktivitas
        ]);

        // Kirim notif ke Owner
        Notification::kirimKeOwner( // Kirim notifikasi ke semua owner
            'Pengguna Dihapus', // Judul notifikasi
            'Admin menghapus pengguna: ' . $username, // Isi pesan
            'danger', // Tipe notifikasi
            route('pengguna.index') // Link ke halaman pengguna
        );

        $pengguna->delete(); // Hapus user dari database

        return redirect()->route('pengguna.index') // Redirect ke halaman index pengguna
            ->with('success', 'Pengguna berhasil dihapus!'); // Pesan sukses
    }
}