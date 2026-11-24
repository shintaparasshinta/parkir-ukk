<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Method dijalankan saat migrate
    {
        Schema::dropIfExists('users'); // Hapus tabel users lama (dari migrasi default) agar bisa dibuat ulang
        
        Schema::create('users', function (Blueprint $table) { // Membuat ulang tabel users dengan struktur baru
            $table->id(); // Primary key
            $table->string('nama', 100); // Nama pengguna (varchar 100)
            $table->string('username', 50)->unique(); // Username unik (varchar 50)
            $table->string('password'); // Password (hash)
            $table->enum('role', ['admin', 'petugas', 'owner']); // Role ditambah 'owner' dibanding versi awal
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('users'); // Hapus tabel users
    }
};