<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarif_parkir', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id');
            $table->enum('jenis_kendaraan', ['Motor', 'Mobil']);
            $table->decimal('tarif', 10, 2);
            $table->timestamps();

            // Foreign key ke tabel area_parkir
            $table->foreign('area_id')
                  ->references('id')
                  ->on('area_parkir')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_parkir');
    }
};