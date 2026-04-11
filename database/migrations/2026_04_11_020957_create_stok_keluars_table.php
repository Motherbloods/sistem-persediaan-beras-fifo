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
        Schema::create('stok_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique()
                ->comment('Nomor otomatis, contoh: SK-20260101-001');
            $table->foreignId('jenis_beras_id')
                ->constrained('jenis_beras')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('Petugas yang mencatat transaksi');
            $table->decimal('jumlah', 10, 2)
                ->comment('Total jumlah beras yang keluar');
            $table->date('tanggal_keluar');
            $table->string('tujuan_distribusi')->nullable()
                ->comment('Nama toko/agen/tujuan distribusi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal_keluar');
            $table->index('jenis_beras_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_keluars');
    }
};
