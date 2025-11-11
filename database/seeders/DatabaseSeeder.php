<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder untuk mengisi semua kelas dengan barang
        $this->call([
            LokasiSeeder::class,           // Buat lokasi/kelas
            ItemSeederComplete::class,     // Buat semua item/barang
            ItemLokasiSeederComplete::class, // Hubungkan item dengan lokasi
        ]);

        $this->command->info('Seeder completed! Semua kelas telah terisi dengan barang-barangnya.');
    }
}
