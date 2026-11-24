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
        Schema::create('log_aktivitas', function (Blueprint $table) { // Membuat tabel log_aktivitas
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // FK ke users (pelaku aktivitas)
            $table->text('aktivitas'); // Deskripsi aktivitas (text)
            $table->timestamps(); // created_at & updated_at

            // Foreign key ke tabel users
            $table->foreign('user_id') // Definisikan FK
                  ->references('id') // Merujuk ke kolom id
                  ->on('users') // Pada tabel users
                  ->onDelete('cascade'); // Hapus log jika user dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('log_aktivitas'); // Hapus tabel log_aktivitas
    }
};