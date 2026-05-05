<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_supplier' => 'SUP-001', 'nama_supplier' => 'PB Sragen'],
        ];

        foreach ($data as $item) {
            Supplier::updateOrCreate(['kode_supplier' => $item['kode_supplier']], $item);
        }

        $this->command->info('  ✔ SupplierSeeder: ' . count($data) . ' supplier berhasil dibuat.');
    }
}