<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FifoQueue extends Model
{
    use HasFactory;

    protected $fillable = [
        'stok_masuk_id',
        'jenis_beras_id',
        'jumlah_awal',
        'jumlah_tersisa',
        'tanggal_masuk',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_awal' => 'decimal:2',
            'jumlah_tersisa' => 'decimal:2',
            'tanggal_masuk' => 'date',
        ];
    }

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class);
    }

    public function jenisBeras()
    {
        return $this->belongsTo(JenisBeras::class);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeUrutFifo($query)
    {
        // FIFO = ambil yang paling lama masuk duluan
        return $query->orderBy('tanggal_masuk', 'asc')->orderBy('id', 'asc');
    }
}