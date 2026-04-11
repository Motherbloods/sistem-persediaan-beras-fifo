<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fifo_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stok_masuk_id')
                ->constrained('stok_masuks')
                ->cascadeOnDelete();
            $table->foreignId('jenis_beras_id')
                ->constrained('jenis_beras')
                ->restrictOnDelete()
                ->comment('Denormalisasi untuk efisiensi query FIFO');
            $table->decimal('jumlah_awal', 10, 2)
                ->comment('Stok awal saat batch ini masuk (tidak berubah)');
            $table->decimal('jumlah_tersisa', 10, 2)
                ->comment('Sisa stok batch ini, dikurangi setiap ada stok keluar');
            $table->date('tanggal_masuk')
                ->comment('Diambil dari stok_masuks.tanggal_masuk, dasar urutan FIFO');
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();

            $table->index(
                ['jenis_beras_id', 'status', 'tanggal_masuk'],
                'idx_fifo_lookup'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fifo_queues');
    }
};
