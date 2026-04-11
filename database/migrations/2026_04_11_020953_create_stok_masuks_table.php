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
        Schema::create('stok_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique()
                ->comment('Nomor otomatis, contoh: SM-20260101-001');
            $table->foreignId('jenis_beras_id')
                ->constrained('jenis_beras')
                ->restrictOnDelete();
            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->restrictOnDelete();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('Petugas yang mencatat transaksi');
            $table->decimal('jumlah', 10, 2)
                ->comment('Jumlah beras masuk dalam satuan jenis beras');
            $table->decimal('harga_beli', 15, 2)->default(0)
                ->comment('Harga beli per satuan saat transaksi ini');
            $table->date('tanggal_masuk');
            $table->date('tanggal_kadaluarsa')->nullable()
                ->comment('Opsional, untuk kontrol kualitas batch');
            $table->string('no_surat_jalan')->nullable()
                ->comment('Nomor surat jalan dari supplier');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal_masuk');
            $table->index('jenis_beras_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_masuks');
    }
};
