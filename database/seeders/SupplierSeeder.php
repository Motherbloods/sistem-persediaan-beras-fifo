<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT Padi Jaya Nusantara',
                'pic' => 'Bapak Hendra',
                'telepon' => '0271-555-1001',
                'email' => 'pjn@padijaya.co.id',
                'alamat' => 'Jl. Raya Solo-Sragen Km.12, Karanganyar, Jawa Tengah',
                'is_active' => true,
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'UD Beras Makmur Boyolali',
                'pic' => 'Ibu Sari',
                'telepon' => '0276-441-2200',
                'email' => 'beras.makmur@gmail.com',
                'alamat' => 'Jl. Pandanaran No.45, Boyolali, Jawa Tengah 57311',
                'is_active' => true,
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'CV Hasil Tani Klaten',
                'pic' => 'Bapak Agus Santoso',
                'telepon' => '0272-334-5678',
                'email' => 'hasiltani.klaten@gmail.com',
                'alamat' => 'Jl. Pemuda No.88, Klaten, Jawa Tengah 57411',
                'is_active' => true,
            ],
            [
                'kode_supplier' => 'SUP-004',
                'nama_supplier' => 'Bulog Sub-divre Surakarta',
                'pic' => 'Ibu Dewi',
                'telepon' => '0271-714-555',
                'email' => 'bulogsubdivreska@bulog.co.id',
                'alamat' => 'Jl. Laksda Adisucipto No.11, Surakarta, Jawa Tengah 57145',
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            Supplier::updateOrCreate(['kode_supplier' => $item['kode_supplier']], $item);
        }

        $this->command->info('  ✔ SupplierSeeder: ' . count($data) . ' supplier berhasil dibuat.');
    }
}