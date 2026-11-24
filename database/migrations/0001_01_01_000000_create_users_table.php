<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint untuk mendefinisikan struktur tabel
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema untuk operasi skema database

return new class extends Migration // Mengembalikan anonymous class turunan Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Method dijalankan saat migrate
    {
        Schema::create('users', function (Blueprint $table) { // Membuat tabel users
            $table->id(); // Primary key auto-increment (bigint unsigned)
            $table->string('nama'); // Kolom nama pengguna (varchar 255)
            $table->string('username')->unique(); // Kolom username unik untuk login
            $table->string('password'); // Kolom password (hash)
            $table->enum('role', ['admin', 'petugas']); // Role awal: hanya admin & petugas (owner ditambahkan belakangan)
            $table->timestamps(); // Kolom created_at & updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) { // Membuat tabel reset password
            $table->string('email')->primary(); // Email sebagai primary key
            $table->string('token'); // Token reset password
            $table->timestamp('created_at')->nullable(); // Waktu pembuatan token (opsional)
        });

        Schema::create('sessions', function (Blueprint $table) { // Membuat tabel session
            $table->string('id')->primary(); // ID session sebagai primary key
            $table->foreignId('user_id')->nullable()->index(); // ID user (nullable), diberi index untuk query cepat
            $table->string('ip_address', 45)->nullable(); // IP address (maks 45 untuk IPv6)
            $table->text('user_agent')->nullable(); // User agent browser
            $table->longText('payload'); // Data session (serialized)
            $table->integer('last_activity')->index(); // Timestamp aktivitas terakhir, diberi index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('sessions'); // Hapus tabel sessions (urutan terbalik)
        Schema::dropIfExists('password_reset_tokens'); // Hapus tabel password_reset_tokens
        Schema::dropIfExists('users'); // Hapus tabel users
    }
};