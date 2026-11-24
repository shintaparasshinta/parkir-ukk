<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint untuk definisi tabel
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // Method dijalankan saat migrate
    {
        Schema::create('cache', function (Blueprint $table) { // Membuat tabel cache
            $table->string('key')->primary(); // Key cache sebagai primary key
            $table->mediumText('value'); // Nilai cache (mediumText)
            $table->bigInteger('expiration')->index(); // Timestamp kadaluarsa, diberi index
        });

        Schema::create('cache_locks', function (Blueprint $table) { // Membuat tabel cache_locks (untuk atomic lock)
            $table->string('key')->primary(); // Key lock sebagai primary key
            $table->string('owner'); // Pemilik lock (ID proses)
            $table->bigInteger('expiration')->index(); // Timestamp kadaluarsa, diberi index
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('cache'); // Hapus tabel cache
        Schema::dropIfExists('cache_locks'); // Hapus tabel cache_locks
    }
};