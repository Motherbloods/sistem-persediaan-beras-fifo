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
        Schema::create('jenis_beras', function (Blueprint $table) {
            $table->id();
            $table->string('kode_beras', 20)->unique()
                ->comment('Kode unik, contoh: BR-001');
            $table->string('nama_beras')
                ->comment('Nama produk beras, contoh: Beras Premium 5kg');
            $table->string('satuan', 20)->default('kg')
                ->comment('Satuan stok: kg, sak, karung, dll');
            $table->decimal('stok_minimum', 10, 2)->default(0)
                ->comment('Ambang batas stok menipis untuk notifikasi');
            $table->decimal('harga_per_satuan', 15, 2)->default(0)
                ->comment('Harga referensi per satuan (bukan untuk transaksi jual)');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_beras');
    }
};
