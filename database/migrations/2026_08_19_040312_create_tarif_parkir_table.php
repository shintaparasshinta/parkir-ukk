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
        Schema::create('tarif_parkir', function (Blueprint $table) { // Membuat tabel tarif_parkir
            $table->id(); // Primary key
            $table->unsignedBigInteger('area_id'); // FK ke area_parkir (unsigned bigint)
            $table->enum('jenis_kendaraan', ['Motor', 'Mobil']); // Jenis kendaraan yang berlaku
            $table->decimal('tarif', 10, 2); // Nilai tarif (10 digit, 2 desimal)
            $table->timestamps(); // created_at & updated_at

            // Foreign key ke tabel area_parkir
            $table->foreign('area_id') // Definisikan FK
                  ->references('id') // Merujuk ke kolom id
                  ->on('area_parkir') // Pada tabel area_parkir
                  ->onDelete('cascade'); // Hapus tarif jika area dihapus (cascade)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('tarif_parkir'); // Hapus tabel tarif_parkir
    }
};