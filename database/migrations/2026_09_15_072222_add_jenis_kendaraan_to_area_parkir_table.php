<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration
{
    public function up(): void // Method dijalankan saat migrate
    {
        Schema::table('area_parkir', function (Blueprint $table) { // Modifikasi tabel area_parkir
            $table->string('jenis_kendaraan')->default('semua')->after('kapasitas'); // Tambah kolom jenis_kendaraan, default 'semua', setelah kapasitas
        });
    }

    public function down(): void // Method dijalankan saat rollback
    {
        Schema::table('area_parkir', function (Blueprint $table) { // Modifikasi tabel area_parkir
            $table->dropColumn('jenis_kendaraan'); // Hapus kolom jenis_kendaraan
        });
    }
};