<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use App\Models\User; // Mengimpor model User
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk hashing password

class UserSeeder extends Seeder // Seeder untuk tabel users
{
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        // 1. Owner
        $owner = User::firstOrCreate( // Buat user owner jika belum ada (idempoten)
            ['username' => 'owner'], // Kondisi pencarian: username
            [
                'nama' => 'Pemilik Parkir', // Nama lengkap
                'password' => Hash::make('password'), // Password di-hash
                'role' => 'owner', // Kolom role (legacy, untuk isOwner())
            ]
        );
        $owner->assignRole('owner'); // WAJIB: Sinkronisasi ke Spatie (untuk hasFeature())

        // 2. Admin
        $admin = User::firstOrCreate( // Buat user admin jika belum ada
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->assignRole('admin'); // WAJIB: Sinkronisasi ke Spatie

        // 3. Petugas
        $petugas = User::firstOrCreate( // Buat user petugas jika belum ada
            ['username' => 'petugas'],
            [
                'nama' => 'Petugas Parkir',
                'password' => Hash::make('password'),
                'role' => 'petugas',
            ]
        );
        $petugas->assignRole('petugas'); // WAJIB: Sinkronisasi ke Spatie
    }
}