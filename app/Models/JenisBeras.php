<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisBeras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jenis_beras';

    protected $fillable = [
        'kode_beras',
        'nama_beras',
        'satuan',
        'stok_minimum',
        'harga_per_satuan',
        'deskripsi',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'stok_minimum' => 'decimal:2',
            'harga_per_satuan' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getStokSaatIniAttribute(): float
    {
        return (float) $this->fifoQueues()
            ->where('status', 'tersedia')
            ->sum('jumlah_tersisa');
    }

    public function getStatusStokAttribute(): string
    {
        $stok = $this->stok_saat_ini;

        if ($stok <= 0) {
            return 'habis';
        }

        if ($stok <= $this->stok_minimum) {
            return 'menipis';
        }

        return 'aman';
    }

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }

    public function stokKeluars()
    {
        return $this->hasMany(StokKeluar::class);
    }

    public function fifoQueues()
    {
        return $this->hasMany(FifoQueue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMenipis($query)
    {
        return $query->whereHas('fifoQueues', function ($q) {
            $q->where('status', 'tersedia');
        })->get()->filter(fn($b) => $b->stok_saat_ini <= $b->stok_minimum && $b->stok_saat_ini > 0);
    }
}