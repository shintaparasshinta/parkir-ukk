<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Ambil checkbox yang dicentang (turn on), sisanya otomatis off
        $permissions = $request->input('permissions', []);
        
        // Update database (menambah atau menghapus izin)
        $role->syncPermissions($permissions);

        return redirect()->back()->with('success', 'Hak akses berhasil diperbarui!');
    }
}