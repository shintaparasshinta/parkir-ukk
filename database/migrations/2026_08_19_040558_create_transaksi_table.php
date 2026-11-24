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
        Schema::create('transaksi', function (Blueprint $table) { // Membuat tabel transaksi
            $table->id(); // Primary key
            $table->string('kode_transaksi', 30)->unique(); // Kode transaksi unik (varchar 30)
            $table->unsignedBigInteger('kendaraan_id');  // FK ke kendaraan (sesuai ERD "kendaraan_id")
            $table->unsignedBigInteger('area_id'); // FK ke area_parkir
            $table->unsignedBigInteger('petugas_id'); // FK ke users (petugas yang memproses)
            $table->dateTime('waktu_masuk'); // Timestamp kendaraan masuk
            $table->dateTime('waktu_keluar')->nullable(); // Timestamp kendaraan keluar (nullable jika masih masuk)
            $table->decimal('tarif', 10, 2); // Tarif transaksi (10 digit, 2 desimal)
            $table->enum('status', ['masuk', 'keluar']); // Status transaksi
            $table->timestamps(); // created_at & updated_at

            // Foreign keys
            $table->foreign('kendaraan_id')  // FK ke kendaraan
                  ->references('id') // Merujuk ke kolom id
                  ->on('kendaraan') // Pada tabel kendaraan
                  ->onDelete('cascade'); // Hapus transaksi jika kendaraan dihapus

            $table->foreign('area_id') // FK ke area_parkir
                  ->references('id') // Merujuk ke kolom id
                  ->on('area_parkir') // Pada tabel area_parkir
                  ->onDelete('cascade'); // Hapus transaksi jika area dihapus

            $table->foreign('petugas_id') // FK ke users
                  ->references('id') // Merujuk ke kolom id
                  ->on('users') // Pada tabel users
                  ->onDelete('cascade'); // Hapus transaksi jika user dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('transaksi'); // Hapus tabel transaksi
    }
};