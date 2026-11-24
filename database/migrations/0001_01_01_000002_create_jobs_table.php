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
        Schema::create('jobs', function (Blueprint $table) { // Membuat tabel jobs (antrian job)
            $table->id(); // Primary key
            $table->string('queue')->index(); // Nama queue, diberi index untuk query cepat
            $table->longText('payload'); // Data job (serialized)
            $table->unsignedSmallInteger('attempts'); // Jumlah percobaan eksekusi job
            $table->unsignedInteger('reserved_at')->nullable(); // Timestamp saat job di-reserve worker
            $table->unsignedInteger('available_at'); // Timestamp kapan job tersedia
            $table->unsignedInteger('created_at'); // Timestamp pembuatan job
        });

        Schema::create('job_batches', function (Blueprint $table) { // Membuat tabel job_batches (batch job)
            $table->string('id')->primary(); // ID batch sebagai primary key
            $table->string('name'); // Nama batch
            $table->integer('total_jobs'); // Total job dalam batch
            $table->integer('pending_jobs'); // Job yang masih pending
            $table->integer('failed_jobs'); // Job yang gagal
            $table->longText('failed_job_ids'); // ID job yang gagal (serialized)
            $table->mediumText('options')->nullable(); // Opsi batch (opsional)
            $table->integer('cancelled_at')->nullable(); // Timestamp pembatalan
            $table->integer('created_at'); // Timestamp pembuatan
            $table->integer('finished_at')->nullable(); // Timestamp selesai
        });

        Schema::create('failed_jobs', function (Blueprint $table) { // Membuat tabel failed_jobs
            $table->id(); // Primary key
            $table->string('uuid')->unique(); // UUID unik job yang gagal
            $table->string('connection'); // Koneksi queue
            $table->string('queue'); // Nama queue
            $table->longText('payload'); // Data job
            $table->longText('exception'); // Exception yang terjadi
            $table->timestamp('failed_at')->useCurrent(); // Timestamp kegagalan (default: sekarang)

            $table->index(['connection', 'queue', 'failed_at']); // Composite index untuk query filtering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // Method dijalankan saat rollback
    {
        Schema::dropIfExists('jobs'); // Hapus tabel jobs
        Schema::dropIfExists('job_batches'); // Hapus tabel job_batches
        Schema::dropIfExists('failed_jobs'); // Hapus tabel failed_jobs
    }
};