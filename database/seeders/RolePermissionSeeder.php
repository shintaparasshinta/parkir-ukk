<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use Spatie\Permission\Models\Role; // Mengimpor model Role dari Spatie Permission
use Spatie\Permission\Models\Permission; // Mengimpor model Permission dari Spatie Permission
use App\Models\User; // Mengimpor model User untuk pembuatan akun inti
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk hashing password

class RolePermissionSeeder extends Seeder // Seeder untuk role, permission, dan akun inti
{
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        // 1. Daftar Permission (Sesuaikan dengan nama route/middleware di web.php)
        $permissions = [ // Array nama permission yang dipakai aplikasi
            // Fitur Admin
            'kelola_kendaraan', // CRUD kendaraan
            'crud_area', // CRUD area parkir
            'crud_tarif', // CRUD tarif
            'crud_user', // CRUD user
            'akses_log', // Akses log aktivitas
            
            // Fitur Petugas
            'input_kendaraan', // Input kendaraan masuk
            'output_kendaraan', // Proses kendaraan keluar
            'cetak_struk', // Cetak struk
            'transaksi', // Akses halaman transaksi

            // Fitur Owner
            'rekap_laporan' // Akses laporan
        ];

        foreach ($permissions as $permission) { // Iterasi tiap nama permission
            Permission::firstOrCreate(['name' => $permission]); // Buat jika belum ada (idempoten)
        }

        // 2. Buat Role
        $roleOwner = Role::firstOrCreate(['name' => 'owner']); // Buat role owner jika belum ada
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']); // Buat role admin jika belum ada
        $rolePetugas = Role::firstOrCreate(['name' => 'petugas']); // Buat role petugas jika belum ada

        // 3. Sinkronisasi Hak Akses Sesuai Permintaanmu
        // OWNER: Hanya Laporan
        $roleOwner->syncPermissions([ // Sync permission untuk owner
            'rekap_laporan' // Owner hanya bisa akses laporan
        ]);

        // ADMIN: Input kendaraan, area, tarif, pengguna, log
        // (Catatan: Admin biasanya butuh 'transaksi' juga untuk memantau, tapi saya ikuti instruksimu)
        $roleAdmin->syncPermissions([ // Sync permission untuk admin
            'kelola_kendaraan', // CRUD kendaraan
            'crud_area', // CRUD area parkir
            'crud_tarif', // CRUD tarif
            'crud_user', // CRUD user
            'akses_log' // Akses log
        ]);

        // PETUGAS: Transaksi & Cetak Struk
        $rolePetugas->syncPermissions([ // Sync permission untuk petugas
            'input_kendaraan', // Input kendaraan masuk
            'output_kendaraan', // Proses kendaraan keluar
            'cetak_struk', // Cetak struk
            'transaksi' // Akses transaksi
        ]);

        // 4. BUAT 3 AKUN INTI
        // Akun Owner
        $owner = User::firstOrCreate( // Buat user owner jika belum ada (idempoten)
            ['username' => 'owner'], // Kondisi pencarian
            [
                'nama' => 'Pemilik Parkir', // Nama lengkap
                'password' => Hash::make('password123'), // Password di-hash
                'role' => 'owner', // Kolom role (legacy, untuk isOwner())
            ]
        );
        $owner->assignRole($roleOwner); // Assign role Spatie (untuk hasFeature())

        // Akun Admin
        $admin = User::firstOrCreate( // Buat user admin jika belum ada
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole($roleAdmin); // Assign role Spatie

        // Akun Petugas
        $petugas = User::firstOrCreate( // Buat user petugas jika belum ada
            ['username' => 'petugas'],
            [
                'nama' => 'Petugas Parkir',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
            ]
        );
        $petugas->assignRole($rolePetugas); // Assign role Spatie
    }
}