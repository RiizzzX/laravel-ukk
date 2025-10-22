<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // ========== LOKASI UTAMA ==========
        
        // 1. RUANG KELAS
        $ruangKelas = Lokasi::firstOrCreate(['nama_lokasi' => 'Ruang Kelas', 'gedung' => 'Gedung A']);
        
        // 2. LABORATORIUM
        $labKomputer = Lokasi::firstOrCreate(['nama_lokasi' => 'Lab Komputer', 'gedung' => 'Gedung B']);
        $labIPAS = Lokasi::firstOrCreate(['nama_lokasi' => 'Lab IPAS', 'gedung' => 'Gedung B']);
        $labBahasa = Lokasi::firstOrCreate(['nama_lokasi' => 'Lab Bahasa', 'gedung' => 'Gedung B']);
        
        // 3. MASJID
        $masjid = Lokasi::firstOrCreate(['nama_lokasi' => 'Masjid', 'gedung' => null]);
        
        // 4. PERPUSTAKAAN
        $perpustakaan = Lokasi::firstOrCreate(['nama_lokasi' => 'Perpustakaan', 'gedung' => 'Gedung C']);
        
        // 5. TAMAN
        $taman = Lokasi::firstOrCreate(['nama_lokasi' => 'Taman', 'gedung' => null]);
        
        // 6. UKS (Unit Kesehatan Sekolah)
        $uks = Lokasi::firstOrCreate(['nama_lokasi' => 'UKS', 'gedung' => 'Gedung A']);
        
        // 7. PARKIRAN
        $parkiran = Lokasi::firstOrCreate(['nama_lokasi' => 'Parkiran', 'gedung' => null]);

        // ========== ITEMS PER LOKASI ==========

        // Items untuk Ruang Kelas
        $itemsKelas = [
            'Meja Siswa',
            'Kursi Siswa',
            'Meja Guru',
            'Kursi Guru',
            'Papan Tulis',
            'Spidol',
            'Penghapus',
            'Kipas Angin',
            'AC',
            'Lemari',
            'Rak Buku',
            'Proyektor',
            'Layar Proyektor',
        ];

        foreach ($itemsKelas as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $ruangKelas->id_lokasi
            ]);
        }

        // Items untuk Lab Komputer
        $itemsLabKomputer = [
            'Komputer PC',
            'Monitor',
            'Keyboard',
            'Mouse',
            'CPU',
            'Headset',
            'AC',
            'Proyektor',
            'Kursi Komputer',
            'Meja Komputer',
            'Switch Network',
            'Router',
            'Printer',
            'Papan Tulis',
            'Stabilizer',
            'Kabel LAN',
        ];

        foreach ($itemsLabKomputer as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $labKomputer->id_lokasi
            ]);
        }

        // Items untuk Lab IPAS
        $itemsLabIPAS = [
            'Mikroskop',
            'Tabung Reaksi',
            'Gelas Ukur',
            'Pipet',
            'Bunsen',
            'Rak Tabung',
            'Lemari Alat',
            'Meja Praktikum',
            'Kursi Lab',
            'Wastafel',
            'Alat Peraga',
            'Model Anatomi',
            'Papan Tulis',
            'Lemari Kimia',
        ];

        foreach ($itemsLabIPAS as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $labIPAS->id_lokasi
            ]);
        }

        // Items untuk Lab Bahasa
        $itemsLabBahasa = [
            'Komputer',
            'Headset',
            'Audio Controller',
            'Meja Siswa',
            'Kursi Siswa',
            'AC',
            'Papan Tulis',
            'Proyektor',
            'Speaker',
            'Microphone',
        ];

        foreach ($itemsLabBahasa as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $labBahasa->id_lokasi
            ]);
        }

        // Items untuk Masjid
        $itemsMasjid = [
            'Karpet',
            'Sajadah',
            'Mukena',
            'Al-Quran',
            'Mimbar',
            'Kipas Angin',
            'Lemari',
            'Rak Sepatu',
            'Speaker Masjid',
            'Mic Wireless',
            'Tempat Wudhu',
        ];

        foreach ($itemsMasjid as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $masjid->id_lokasi
            ]);
        }

        // Items untuk Perpustakaan
        $itemsPerpustakaan = [
            'Rak Buku',
            'Buku Pelajaran',
            'Buku Fiksi',
            'Buku Non-Fiksi',
            'Meja Baca',
            'Kursi Baca',
            'Komputer Katalog',
            'AC',
            'Kipas Angin',
            'Lemari Arsip',
            'Scanner',
            'Printer',
        ];

        foreach ($itemsPerpustakaan as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $perpustakaan->id_lokasi
            ]);
        }

        // Items untuk Taman
        $itemsTaman = [
            'Bangku Taman',
            'Tempat Sampah',
            'Pot Bunga',
            'Tanaman Hias',
            'Lampu Taman',
            'Pagar',
            'Selang Air',
            'Alat Kebun',
        ];

        foreach ($itemsTaman as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $taman->id_lokasi
            ]);
        }

        // Items untuk UKS
        $itemsUKS = [
            'Tempat Tidur',
            'Bantal',
            'Selimut',
            'Lemari Obat',
            'Obat-obatan',
            'Tensimeter',
            'Termometer',
            'Timbangan',
            'Pengukur Tinggi',
            'Meja Dokter',
            'Kursi',
            'Wastafel',
            'Kotak P3K',
        ];

        foreach ($itemsUKS as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $uks->id_lokasi
            ]);
        }

        // Items untuk Parkiran
        $itemsParkiran = [
            'Rambu Parkir',
            'Garis Parkir',
            'Palang Pintu',
            'CCTV',
            'Lampu Parkir',
            'Tempat Helm',
            'Pagar Pembatas',
        ];

        foreach ($itemsParkiran as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $parkiran->id_lokasi
            ]);
        }

        echo "✅ Data lokasi dan item berhasil ditambahkan!\n";
        echo "📍 Total Lokasi: 9 (Ruang Kelas, Lab Komputer, Lab IPAS, Lab Bahasa, Masjid, Perpustakaan, Taman, UKS, Parkiran)\n";
    }
}
