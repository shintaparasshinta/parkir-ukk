<?php

namespace App\Http\Controllers; // Menentukan namespace controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Spatie\Permission\Models\Role; // Mengimpor model Role dari package Spatie Permission
use Spatie\Permission\Models\Permission; // Mengimpor model Permission dari package Spatie Permission

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
        // Ambil checkbox yang dicentang (turn on), sisanya otomatis off
        $permissions = $request->input('permissions', []); // Ambil array permission dari input, default array kosong
        
        // Update database (menambah atau menghapus izin)
        $role->syncPermissions($permissions); // Sinkronkan permission role: yang tidak ada di array akan dihapus

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui!'); // Redirect kembali dengan pesan sukses
    }
}