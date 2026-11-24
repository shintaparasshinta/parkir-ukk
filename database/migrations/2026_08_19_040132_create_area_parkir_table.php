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
        Schema::create('area_parkir', function (Blueprint $table) { // Membuat tabel area_parkir
            $table->id(); // Primary key
            $table->string('nama_area', 100); // Nama area parkir (varchar 100)
            $table->integer('kapasitas'); // Kapasitas maksimum area
            $table->enum('status', ['tersedia', 'penuh', 'hampir_penuh']); // Status keterisian area
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('area_parkir'); // Hapus tabel area_parkir
    }
};