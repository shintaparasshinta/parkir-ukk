<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat Admin
        User::create([
            'nama' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Buat Petugas
        User::create([
            'nama' => 'Petugas Parkir',
            'username' => 'petugas',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        // Buat Owner
        User::create([
            'nama' => 'Pemilik Parkir',
            'username' => 'owner',
            'password' => Hash::make('password123'),
            'role' => 'owner',
        ]);

        // Buat petugas tambahan (contoh)
        User::create([
            'nama' => 'Budi Santoso',
            'username' => 'budi',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        User::create([
            'nama' => 'Siti Rahayu',
            'username' => 'siti',
            'password' => Hash::make('password123'),
            'role' => 'petugas',
        ]);

        $this->command->info('✅ User seeder berhasil dijalankan!');
        $this->command->info('📌 Akun yang dibuat:');
        $this->command->info('   - Admin (username: admin, password: password123)');
        $this->command->info('   - Petugas (username: petugas, password: password123)');
        $this->command->info('   - Owner (username: owner, password: password123)');
        $this->command->info('   - Budi (username: budi, password: password123)');
        $this->command->info('   - Siti (username: siti, password: password123)');
    }
}