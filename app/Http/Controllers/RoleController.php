<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Spatie\Permission\Models\Role; // Mengimpor model Role dari package Spatie Permission
use Spatie\Permission\Models\Permission; // Mengimpor model Permission dari package Spatie Permission
use App\Models\LogAktivitas; // Mengimpor model LogAktivitas untuk mencatat aktivitas
use App\Models\Notification; // Mengimpor model Notification untuk notifikasi ke owner

class RoleController extends Controller // Mendefinisikan controller manajemen role & permission
{
    public function index() // Method untuk menampilkan halaman manajemen role
    {
        $roles = Role::all(); // Ambil semua role
        $permissions = Permission::all(); // Ambil semua permission yang tersedia
        return view('admin.roles.index', compact('roles', 'permissions')); // Kirim data ke view admin.roles.index
    }

    public function update(Request $request, Role $role) // Method untuk memperbarui permission suatu role (route model binding)
    {
        $permissions = $request->input('permissions', []); // Ambil array permission dari input, default array kosong
        $role->syncPermissions($permissions); // Sinkronkan permission role: yang tidak ada di array akan dihapus

        // Catat log
        LogAktivitas::create([ // Mencatat log perubahan hak akses role
            'user_id' => auth()->user()->id, // ID user yang sedang login
            'aktivitas' => 'Mengubah hak akses role: ' . $role->name, // Deskripsi aktivitas
        ]);

        // Kirim notifikasi ke owner
        Notification::kirimKeOwner( // Kirim notif ke semua owner
            'Perubahan Hak Akses Role', // Judul notifikasi
            'Admin mengubah daftar fitur untuk role: ' . strtoupper($role->name), // Isi pesan
            'danger', // Tipe notifikasi
            route('roles.index') // Link ke halaman manajemen role
        );

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui!'); // Redirect kembali dengan pesan sukses
    }
}