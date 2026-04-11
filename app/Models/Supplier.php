<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'pic',
        'telepon',
        'email',
        'alamat',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function stokMasuks()
    {
        return $this->hasMany(StokMasuk::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}