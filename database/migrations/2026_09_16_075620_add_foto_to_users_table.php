<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration
{
    public function up(): void // Method dijalankan saat migrate
    {
        Schema::table('users', function (Blueprint $table) { // Modifikasi tabel users
            $table->string('foto')->nullable()->after('role'); // Tambah kolom foto (nullable) setelah kolom role
        });
    }

    public function down(): void // Method dijalankan saat rollback
    {
        Schema::table('users', function (Blueprint $table) { // Modifikasi tabel users
            $table->dropColumn('foto'); // Hapus kolom foto
        });
    }
};