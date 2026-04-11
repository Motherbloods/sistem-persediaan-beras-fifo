<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBeras;

class JenisBerasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_beras' => 'BR-001',
                'nama_beras' => 'Beras IR64 Medium',
                'satuan' => 'kg',
                'stok_minimum' => 500,
                'harga_per_satuan' => 11500,
                'deskripsi' => 'Beras IR64 kualitas medium, cocok untuk kebutuhan rumah tangga.',
                'is_active' => true,
            ],
            [
                'kode_beras' => 'BR-002',
                'nama_beras' => 'Beras Pandan Wangi Premium',
                'satuan' => 'kg',
                'stok_minimum' => 300,
                'harga_per_satuan' => 16000,
                'deskripsi' => 'Beras pandan wangi kelas premium, pulen dan harum.',
                'is_active' => true,
            ],
            [
                'kode_beras' => 'BR-003',
                'nama_beras' => 'Beras SLYP (Super Long Yellow Premium)',
                'satuan' => 'kg',
                'stok_minimum' => 200,
                'harga_per_satuan' => 13000,
                'deskripsi' => 'Beras bulir panjang, cocok untuk nasi goreng dan restoran.',
                'is_active' => true,
            ],
            [
                'kode_beras' => 'BR-004',
                'nama_beras' => 'Beras Setra Ramos',
                'satuan' => 'kg',
                'stok_minimum' => 400,
                'harga_per_satuan' => 14500,
                'deskripsi' => 'Beras setra ramos kualitas unggul, nasi pulen dan tidak lengket.',
                'is_active' => true,
            ],
            [
                'kode_beras' => 'BR-005',
                'nama_beras' => 'Beras Pecah Kulit (Merah)',
                'satuan' => 'kg',
                'stok_minimum' => 100,
                'harga_per_satuan' => 18000,
                'deskripsi' => 'Beras merah pecah kulit, kaya serat, untuk segmen kesehatan.',
                'is_active' => true,
            ],
            [
                'kode_beras' => 'BR-006',
                'nama_beras' => 'Beras Raskin / SPHP',
                'satuan' => 'kg',
                'stok_minimum' => 1000,
                'harga_per_satuan' => 9500,
                'deskripsi' => 'Beras program stabilisasi harga, distribusi untuk masyarakat.',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            JenisBeras::updateOrCreate(['kode_beras' => $item['kode_beras']], $item);
        }

        $this->command->info('  ✔ JenisBerasSeeder: ' . count($data) . ' jenis beras berhasil dibuat.');
    }
}