<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class ItemLokasiSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        \DB::table('list_lokasi')->truncate();
        \DB::table('items')->truncate();
        \DB::table('lokasi')->truncate();

        // Lokasi data
        $lokasi = [
            ['id_lokasi' => 1, 'nama_lokasi' => 'Ruang Kelas', 'gedung' => 'Gedung A'],
            ['id_lokasi' => 2, 'nama_lokasi' => 'Lab Komputer', 'gedung' => 'Gedung B'],
            ['id_lokasi' => 3, 'nama_lokasi' => 'Lab IPAS', 'gedung' => 'Gedung B'],
            ['id_lokasi' => 4, 'nama_lokasi' => 'Lab Bahasa', 'gedung' => 'Gedung B'],
            ['id_lokasi' => 5, 'nama_lokasi' => 'Masjid', 'gedung' => 'Gedung C'],
            ['id_lokasi' => 6, 'nama_lokasi' => 'Perpustakaan', 'gedung' => 'Gedung D'],
            ['id_lokasi' => 7, 'nama_lokasi' => 'Taman', 'gedung' => 'Area Outdoor'],
            ['id_lokasi' => 8, 'nama_lokasi' => 'UKS', 'gedung' => 'Gedung A'],
            ['id_lokasi' => 9, 'nama_lokasi' => 'Parkiran', 'gedung' => 'Area Outdoor'],
        ];

        foreach ($lokasi as $lok) {
            Lokasi::create($lok);
        }

        // Items yang umum dipakai di banyak lokasi
        $commonItems = [
            ['nama_item' => 'Kursi', 'deskripsi' => 'Kursi standar untuk siswa/pegawai', 'lokasi_ids' => [1,2,3,4,5,6,8]],
            ['nama_item' => 'Meja', 'deskripsi' => 'Meja standar untuk belajar/bekerja', 'lokasi_ids' => [1,2,3,4,5,6,8]],
            ['nama_item' => 'Lampu LED', 'deskripsi' => 'Lampu penerangan ruangan', 'lokasi_ids' => [1,2,3,4,5,6,8]],
            ['nama_item' => 'Kipas Angin', 'deskripsi' => 'Kipas angin dinding/standing', 'lokasi_ids' => [1,2,3,4,5,6,8]],
            ['nama_item' => 'AC', 'deskripsi' => 'Air Conditioner', 'lokasi_ids' => [2,3,4,6,8]],
            ['nama_item' => 'Proyektor', 'deskripsi' => 'Proyektor untuk presentasi', 'lokasi_ids' => [1,2,4,6]],
            ['nama_item' => 'Papan Tulis', 'deskripsi' => 'Whiteboard/blackboard', 'lokasi_ids' => [1,2,3,4]],
            ['nama_item' => 'Spidol', 'deskripsi' => 'Spidol whiteboard', 'lokasi_ids' => [1,2,3,4]],
            ['nama_item' => 'Penghapus', 'deskripsi' => 'Penghapus papan tulis', 'lokasi_ids' => [1,2,3,4]],
        ];

        // Specific items per lokasi
        $specificItems = [
            // Lab Komputer
            ['nama_item' => 'Komputer PC', 'deskripsi' => 'Unit komputer desktop', 'lokasi_ids' => [2]],
            ['nama_item' => 'Mouse', 'deskripsi' => 'Mouse komputer', 'lokasi_ids' => [2]],
            ['nama_item' => 'Keyboard', 'deskripsi' => 'Keyboard komputer', 'lokasi_ids' => [2]],
            ['nama_item' => 'Monitor', 'deskripsi' => 'Monitor LCD/LED', 'lokasi_ids' => [2]],
            ['nama_item' => 'Stabilizer', 'deskripsi' => 'Stabilizer listrik', 'lokasi_ids' => [2]],
            
            // Lab IPAS
            ['nama_item' => 'Mikroskop', 'deskripsi' => 'Mikroskop untuk praktikum', 'lokasi_ids' => [3]],
            ['nama_item' => 'Tabung Reaksi', 'deskripsi' => 'Tabung reaksi kimia', 'lokasi_ids' => [3]],
            ['nama_item' => 'Bunsen Burner', 'deskripsi' => 'Pembakar bunsen', 'lokasi_ids' => [3]],
            ['nama_item' => 'Gelas Ukur', 'deskripsi' => 'Gelas ukur berbagai ukuran', 'lokasi_ids' => [3]],
            
            // Lab Bahasa
            ['nama_item' => 'Headset', 'deskripsi' => 'Headset untuk listening', 'lokasi_ids' => [4]],
            ['nama_item' => 'Audio System', 'deskripsi' => 'Sistem audio ruangan', 'lokasi_ids' => [4]],
            ['nama_item' => 'Booth', 'deskripsi' => 'Booth praktik bahasa', 'lokasi_ids' => [4]],
            
            // Masjid
            ['nama_item' => 'Karpet', 'deskripsi' => 'Karpet sajadah', 'lokasi_ids' => [5]],
            ['nama_item' => 'Mukena', 'deskripsi' => 'Mukena untuk jamaah', 'lokasi_ids' => [5]],
            ['nama_item' => 'Al-Quran', 'deskripsi' => 'Mushaf Al-Quran', 'lokasi_ids' => [5]],
            ['nama_item' => 'Sound System', 'deskripsi' => 'Sound system masjid', 'lokasi_ids' => [5]],
            
            // Perpustakaan
            ['nama_item' => 'Rak Buku', 'deskripsi' => 'Rak penyimpanan buku', 'lokasi_ids' => [6]],
            ['nama_item' => 'Buku Pelajaran', 'deskripsi' => 'Koleksi buku pelajaran', 'lokasi_ids' => [6]],
            ['nama_item' => 'Buku Referensi', 'deskripsi' => 'Buku referensi dan ensiklopedia', 'lokasi_ids' => [6]],
            ['nama_item' => 'Komputer Katalog', 'deskripsi' => 'Komputer untuk pencarian buku', 'lokasi_ids' => [6]],
            
            // Taman
            ['nama_item' => 'Bangku Taman', 'deskripsi' => 'Bangku untuk duduk di taman', 'lokasi_ids' => [7]],
            ['nama_item' => 'Tempat Sampah', 'deskripsi' => 'Tempat sampah organik/anorganik', 'lokasi_ids' => [7,9]],
            ['nama_item' => 'Pot Bunga', 'deskripsi' => 'Pot tanaman hias', 'lokasi_ids' => [7]],
            
            // UKS
            ['nama_item' => 'Tempat Tidur', 'deskripsi' => 'Tempat tidur pasien', 'lokasi_ids' => [8]],
            ['nama_item' => 'Kotak P3K', 'deskripsi' => 'Kotak obat pertolongan pertama', 'lokasi_ids' => [8]],
            ['nama_item' => 'Timbangan', 'deskripsi' => 'Timbangan berat badan', 'lokasi_ids' => [8]],
            ['nama_item' => 'Pengukur Tinggi', 'deskripsi' => 'Alat pengukur tinggi badan', 'lokasi_ids' => [8]],
            
            // Parkiran
            ['nama_item' => 'Rambu Parkir', 'deskripsi' => 'Rambu petunjuk parkir', 'lokasi_ids' => [9]],
            ['nama_item' => 'Portal', 'deskripsi' => 'Portal pembatas kendaraan', 'lokasi_ids' => [9]],
        ];

        $allItems = array_merge($commonItems, $specificItems);

        foreach ($allItems as $itemData) {
            $lokasiIds = $itemData['lokasi_ids'];
            unset($itemData['lokasi_ids']);
            
            $item = Item::create($itemData);
            
            // Attach ke multiple lokasi via pivot table
            $item->lokasis()->attach($lokasiIds);
        }

        $this->command->info('✅ Seeder completed: ' . count($allItems) . ' items dengan sistem many-to-many');
    }
}
