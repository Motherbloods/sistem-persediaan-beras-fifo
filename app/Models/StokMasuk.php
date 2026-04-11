<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_transaksi',
        'jenis_beras_id',
        'supplier_id',
        'user_id',
        'jumlah',
        'harga_beli',
        'tanggal_masuk',
        'tanggal_kadaluarsa',
        'no_surat_jalan',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'harga_beli' => 'decimal:2',
            'tanggal_masuk' => 'date',
            'tanggal_kadaluarsa' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (StokMasuk $stokMasuk) {
            FifoQueue::create([
                'stok_masuk_id' => $stokMasuk->id,
                'jenis_beras_id' => $stokMasuk->jenis_beras_id,
                'jumlah_awal' => $stokMasuk->jumlah,
                'jumlah_tersisa' => $stokMasuk->jumlah,
                'tanggal_masuk' => $stokMasuk->tanggal_masuk,
                'status' => 'tersedia',
            ]);
        });
    }

    public function jenisBeras()
    {
        return $this->belongsTo(JenisBeras::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fifoQueue()
    {
        return $this->hasOne(FifoQueue::class);
    }

    public static function generateNoTransaksi(): string
    {
        $prefix = 'SM-' . now()->format('Ymd') . '-';
        $last = self::where('no_transaksi', 'like', $prefix . '%')
            ->orderByDesc('no_transaksi')
            ->value('no_transaksi');

        $urutan = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }
}