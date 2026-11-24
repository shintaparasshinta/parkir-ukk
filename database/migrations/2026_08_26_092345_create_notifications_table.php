<?php

use Illuminate\Database\Migrations\Migration; // Mengimpor kelas dasar Migration
use Illuminate\Database\Schema\Blueprint; // Mengimpor Blueprint
use Illuminate\Support\Facades\Schema; // Mengimpor facade Schema

return new class extends Migration // Anonymous class turunan Migration
{
    public function up() // Method dijalankan saat migrate
    {
        Schema::create('notifications', function (Blueprint $table) { // Membuat tabel notifications (custom, bukan bawaan Laravel)
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // FK ke users (penerima notifikasi)
            $table->string('title'); // Judul notifikasi
            $table->text('message'); // Isi pesan notifikasi
            $table->string('type')->default('info'); // Tipe notifikasi: info, success, warning, danger
            $table->string('link')->nullable(); // URL tujuan saat diklik (opsional)
            $table->boolean('is_read')->default(false); // Status sudah dibaca (default: false)
            $table->timestamps(); // created_at & updated_at

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // FK + cascade delete
        });
    }

    public function down() // Method dijalankan saat rollback
    {
        Schema::dropIfExists('notifications'); // Hapus tabel notifications
    }
};