<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBeras;

class JenisBerasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_beras' => 'BR-10KG', 'nama_beras' => 'Beras Premium 10 Kg', 'satuan' => 'kg'],
            ['kode_beras' => 'BR-20KG', 'nama_beras' => 'Beras Premium 20 Kg', 'satuan' => 'kg'],
        ];

        foreach ($data as $item) {
            JenisBeras::updateOrCreate(['kode_beras' => $item['kode_beras']], $item);
        }

        $this->command->info('  ✔ JenisBerasSeeder: ' . count($data) . ' jenis beras berhasil dibuat.');
    }
}