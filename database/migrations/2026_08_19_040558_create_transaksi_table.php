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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 30)->unique();
            $table->unsignedBigInteger('kendaraan_id');  // PERHATIKAN: sesuai ERD "kendaraan_id"
            $table->unsignedBigInteger('area_id');
            $table->unsignedBigInteger('petugas_id');
            $table->dateTime('waktu_masuk');
            $table->dateTime('waktu_keluar')->nullable();
            $table->decimal('tarif', 10, 2);
            $table->enum('status', ['masuk', 'keluar']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('kendaraan_id')  // PERHATIKAN: sesuai ERD "kendaraan_id"
                  ->references('id')
                  ->on('kendaraan')
                  ->onDelete('cascade');

            $table->foreign('area_id')
                  ->references('id')
                  ->on('area_parkir')
                  ->onDelete('cascade');

            $table->foreign('petugas_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};