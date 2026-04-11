<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('  Seeding: Sistem Persediaan Beras');
        $this->command->info('  CV Santri Abadi Indonesia');
        $this->command->info('========================================');

        $this->call([
            UserSeeder::class,
            JenisBerasSeeder::class,
            SupplierSeeder::class,
        ]);

        $this->command->info('========================================');
        $this->command->info('  Seeding selesai!');
        $this->command->info('');
        $this->command->info('  Login Admin:');
        $this->command->info('  Email    : admin@santriabadi.com');
        $this->command->info('  Password : admin123');
        $this->command->info('');
        $this->command->info('  Login Petugas Gudang:');
        $this->command->info('  Email    : gudang@santriabadi.com');
        $this->command->info('  Password : gudang123');
        $this->command->info('========================================');
        $this->command->info('');
    }
}
