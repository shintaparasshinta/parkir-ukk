<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // 1. Daftar Semua Fitur
        $permissions = [
            // Admin
            'crud_user',
            'crud_tarif',
            'crud_area',
            'kelola_kendaraan',
            'akses_log',

            // Petugas
            'transaksi',
            'cetak_struk',
            'input_kendaraan',
            'output_kendaraan',

            // Owner
            'rekap_laporan',

            // Tambahan
            'hapus_transaksi',
        ];

        // Pakai firstOrCreate supaya tidak error kalau sudah ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Buat Role (pakai firstOrCreate)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $owner = Role::firstOrCreate(['name' => 'owner']);

        // 3. Berikan Izin SESUAI TABEL SOAL SPK
        // Pakai syncPermissions supaya tidak menumpuk permission lama
        $admin->syncPermissions([
            'crud_user',
            'crud_tarif',
            'crud_area',
            'kelola_kendaraan',
            'akses_log',
        ]);

        $petugas->syncPermissions([
            'transaksi',
            'cetak_struk',
            'input_kendaraan',
            'output_kendaraan',
        ]);

        $owner->syncPermissions([
            'rekap_laporan',
        ]);
    }
}