<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StokKeluar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'no_transaksi',
        'jenis_beras_id',
        'user_id',
        'jumlah',
        'tanggal_keluar',
        'tujuan_distribusi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'tanggal_keluar' => 'date',
        ];
    }

    public function jenisBeras()
    {
        return $this->belongsTo(JenisBeras::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateNoTransaksi(): string
    {
        $prefix = 'SK-' . now()->format('Ymd') . '-';
        $last = self::where('no_transaksi', 'like', $prefix . '%')
            ->orderByDesc('no_transaksi')
            ->value('no_transaksi');

        $urutan = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }
}