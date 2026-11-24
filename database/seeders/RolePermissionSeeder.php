<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use Spatie\Permission\Models\Role; // Mengimpor model Role dari Spatie Permission
use Spatie\Permission\Models\Permission; // Mengimpor model Permission dari Spatie Permission

class RolePermissionSeeder extends Seeder // Mendefinisikan seeder untuk role & permission Spatie
{
    public function run() // Method utama yang dijalankan saat seeder dieksekusi
    {
        // 1. Daftar Semua Fitur
        $permissions = [ // Array berisi semua permission yang akan dibuat
            // Admin
            'crud_user', // Izin CRUD user
            'crud_tarif', // Izin CRUD tarif
            'crud_area', // Izin CRUD area parkir
            'kelola_kendaraan', // Izin kelola kendaraan
            'akses_log', // Izin akses log aktivitas

            // Petugas
            'transaksi', // Izin akses transaksi
            'cetak_struk', // Izin cetak struk
            'input_kendaraan', // Izin input kendaraan masuk
            'output_kendaraan', // Izin proses kendaraan keluar

            // Owner
            'rekap_laporan', // Izin akses rekap laporan

            // Tambahan
            'hapus_transaksi', // Izin hapus transaksi (khusus, biasanya diberikan selektif)
        ];

        // Pakai firstOrCreate supaya tidak error kalau sudah ada
        foreach ($permissions as $permission) { // Iterasi tiap nama permission
            Permission::firstOrCreate(['name' => $permission]); // Buat jika belum ada, skip jika sudah ada (idempoten)
        }

        // 2. Buat Role (pakai firstOrCreate)
        $admin = Role::firstOrCreate(['name' => 'admin']); // Buat role admin jika belum ada
        $petugas = Role::firstOrCreate(['name' => 'petugas']); // Buat role petugas jika belum ada
        $owner = Role::firstOrCreate(['name' => 'owner']); // Buat role owner jika belum ada

        // 3. Berikan Izin SESUAI TABEL SOAL SPK
        // Pakai syncPermissions supaya tidak menumpuk permission lama
        $admin->syncPermissions([ // Set permission untuk role admin
            'crud_user', // CRUD user
            'crud_tarif', // CRUD tarif
            'crud_area', // CRUD area
            'kelola_kendaraan', // Kelola kendaraan
            'akses_log', // Akses log
        ]);

        $petugas->syncPermissions([ // Set permission untuk role petugas
            'transaksi', // Akses transaksi
            'cetak_struk', // Cetak struk
            'input_kendaraan', // Input kendaraan masuk
            'output_kendaraan', // Proses kendaraan keluar
        ]);

        $owner->syncPermissions([ // Set permission untuk role owner
            'rekap_laporan', // Akses rekap laporan
        ]);
    }
}   