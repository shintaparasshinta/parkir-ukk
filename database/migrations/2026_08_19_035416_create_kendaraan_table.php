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
        Schema::create('kendaraan', function (Blueprint $table) { // Membuat tabel kendaraan (nama tunggal sesuai konvensi Indonesia)
            $table->id(); // Primary key
            $table->string('plat_nomor', 20)->unique(); // Plat nomor unik (varchar 20)
            $table->string('pemilik', 100); // Nama pemilik (varchar 100)
            $table->enum('jenis_kendaraan', ['Motor', 'Mobil']); // Jenis kendaraan: Motor atau Mobil
            $table->string('merk', 50); // Merk kendaraan (varchar 50)
            $table->string('warna', 30); // Warna kendaraan (varchar 30)
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('kendaraan'); // Hapus tabel kendaraan
    }
};