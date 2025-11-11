<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Lokasi;
use App\Models\ListLokasi;
use Illuminate\Database\Seeder;

class ItemListLokasiSeeder extends Seeder
{
    public function run(): void
    {
        // Buat lokasi dulu
        $lokasis = [
            ['nama_lokasi' => 'Ruang Kelas 10-1', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Ruang Kelas 10-2', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Ruang Kelas 11-1', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Lab Komputer', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Lab IPAS', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Lab Bahasa', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Perpustakaan', 'gedung' => 'Gedung C'],
            ['nama_lokasi' => 'Masjid', 'gedung' => 'Gedung C'],
            ['nama_lokasi' => 'UKS', 'gedung' => 'Gedung A'],
        ];

        $this->command->info('Creating lokasi...');
        foreach ($lokasis as $lok) {
            Lokasi::firstOrCreate(['nama_lokasi' => $lok['nama_lokasi']], $lok);
        }

        // Buat items (CUMA 1 RECORD PER ITEM)
        $items = [
            ['nama_item' => 'Kipas Angin', 'lokasi' => 1, 'deskripsi' => 'Kipas angin standing'],
            ['nama_item' => 'AC', 'lokasi' => 1, 'deskripsi' => 'Air Conditioner'],
            ['nama_item' => 'Kursi', 'lokasi' => 1, 'deskripsi' => 'Kursi standar'],
            ['nama_item' => 'Meja', 'lokasi' => 1, 'deskripsi' => 'Meja belajar'],
            ['nama_item' => 'Lampu LED', 'lokasi' => 1, 'deskripsi' => 'Lampu penerangan'],
            ['nama_item' => 'Papan Tulis', 'lokasi' => 1, 'deskripsi' => 'Whiteboard'],
            ['nama_item' => 'Proyektor', 'lokasi' => 1, 'deskripsi' => 'Proyektor presentasi'],
            ['nama_item' => 'Komputer', 'lokasi' => 1, 'deskripsi' => 'Komputer desktop'],
            ['nama_item' => 'Printer', 'lokasi' => 1, 'deskripsi' => 'Printer laser'],
            ['nama_item' => 'Mikroskop', 'lokasi' => 1, 'deskripsi' => 'Mikroskop lab'],
            ['nama_item' => 'Rak Buku', 'lokasi' => 1, 'deskripsi' => 'Rak penyimpanan buku'],
            ['nama_item' => 'Karpet', 'lokasi' => 1, 'deskripsi' => 'Karpet sajadah'],
            ['nama_item' => 'Tempat Tidur', 'lokasi' => 1, 'deskripsi' => 'Tempat tidur UKS'],
        ];

        $this->command->info('Creating items...');
        foreach ($items as $itemData) {
            Item::firstOrCreate(['nama_item' => $itemData['nama_item']], $itemData);
        }

        // Mapping item ke multiple lokasi via list_lokasi
        $mappings = [
            'Kipas Angin' => ['Ruang Kelas 10-1', 'Ruang Kelas 10-2', 'Ruang Kelas 11-1', 'Lab Komputer', 'Lab IPAS', 'Lab Bahasa', 'Perpustakaan'],
            'AC' => ['Lab Komputer', 'Lab IPAS', 'Lab Bahasa', 'Perpustakaan', 'UKS'],
            'Kursi' => ['Ruang Kelas 10-1', 'Ruang Kelas 10-2', 'Ruang Kelas 11-1', 'Lab Komputer', 'Lab IPAS', 'Lab Bahasa', 'Perpustakaan', 'UKS'],
            'Meja' => ['Ruang Kelas 10-1', 'Ruang Kelas 10-2', 'Ruang Kelas 11-1', 'Lab Komputer', 'Lab IPAS', 'Lab Bahasa', 'Perpustakaan', 'UKS'],
            'Lampu LED' => ['Ruang Kelas 10-1', 'Ruang Kelas 10-2', 'Ruang Kelas 11-1', 'Lab Komputer', 'Lab IPAS', 'Lab Bahasa', 'Perpustakaan', 'Masjid', 'UKS'],
            'Papan Tulis' => ['Ruang Kelas 10-1', 'Ruang Kelas 10-2', 'Ruang Kelas 11-1', 'Lab IPAS', 'Lab Bahasa'],
            'Proyektor' => ['Ruang Kelas 10-1', 'Lab Komputer', 'Lab Bahasa', 'Perpustakaan'],
            'Komputer' => ['Lab Komputer', 'Perpustakaan'],
            'Printer' => ['Lab Komputer', 'Perpustakaan'],
            'Mikroskop' => ['Lab IPAS'],
            'Rak Buku' => ['Perpustakaan'],
            'Karpet' => ['Masjid'],
            'Tempat Tidur' => ['UKS'],
        ];

        $this->command->info('Mapping items to lokasi...');
        foreach ($mappings as $itemName => $lokasiNames) {
            $item = Item::where('nama_item', $itemName)->first();
            
            foreach ($lokasiNames as $lokasiName) {
                $lokasi = Lokasi::where('nama_lokasi', $lokasiName)->first();
                
                if ($item && $lokasi) {
                    ListLokasi::firstOrCreate([
                        'id_item' => $item->id_item,
                        'id_lokasi' => $lokasi->id_lokasi,
                    ]);
                }
            }
        }

        $itemCount = Item::count();
        $mappingCount = ListLokasi::count();
        $this->command->info("✅ Seeder completed:");
        $this->command->info("   - {$itemCount} unique items");
        $this->command->info("   - {$mappingCount} item-lokasi mappings");
        $this->command->info("   Example: 1 'Kipas Angin' dipakai di " . count($mappings['Kipas Angin']) . " lokasi");
    }
}
