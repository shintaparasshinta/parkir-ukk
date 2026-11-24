<?php

namespace Database\Seeders; // Menentukan namespace seeder

use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Mengimpor trait untuk menonaktifkan event model (tidak dipakai)
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder
use App\Models\User; // Mengimpor model User
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk hashing password

class UserSeeder extends Seeder // Mendefinisikan seeder untuk tabel users
{
    /**
     * Run the database seeds.
     */
    public function run(): void // Method utama yang dijalankan saat seeder dieksekusi
    {
        // Buat Admin
        User::create([ // Membuat akun admin
            'nama' => 'Administrator', // Nama lengkap
            'username' => 'admin', // Username unik
            'password' => Hash::make('password123'), // Password di-hash
            'role' => 'admin', // Role: admin
        ]);

        // Buat Petugas
        User::create([ // Membuat akun petugas
            'nama' => 'Petugas Parkir', // Nama lengkap
            'username' => 'petugas', // Username unik
            'password' => Hash::make('password123'), // Password di-hash
            'role' => 'petugas', // Role: petugas
        ]);

        // Buat Owner
        User::create([ // Membuat akun owner
            'nama' => 'Pemilik Parkir', // Nama lengkap
            'username' => 'owner', // Username unik
            'password' => Hash::make('password123'), // Password di-hash
            'role' => 'owner', // Role: owner
        ]);

        // Buat petugas tambahan (contoh)
        User::create([ // Membuat akun petugas tambahan
            'nama' => 'Budi Santoso', // Nama lengkap
            'username' => 'budi', // Username unik
            'password' => Hash::make('password123'), // Password di-hash
            'role' => 'petugas', // Role: petugas
        ]);

        User::create([ // Membuat akun petugas tambahan kedua
            'nama' => 'Siti Rahayu', // Nama lengkap
            'username' => 'siti', // Username unik
            'password' => Hash::make('password123'), // Password di-hash
            'role' => 'petugas', // Role: petugas
        ]);

        $this->command->info('✅ User seeder berhasil dijalankan!'); // Pesan sukses
        $this->command->info('📌 Akun yang dibuat:'); // Info header daftar akun
        $this->command->info('   - Admin (username: admin, password: password123)'); // Info akun admin
        $this->command->info('   - Petugas (username: petugas, password: password123)'); // Info akun petugas
        $this->command->info('   - Owner (username: owner, password: password123)'); // Info akun owner
        $this->command->info('   - Budi (username: budi, password: password123)'); // Info akun budi
        $this->command->info('   - Siti (username: siti, password: password123)'); // Info akun siti
    }
}