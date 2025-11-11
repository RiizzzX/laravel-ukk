<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Lokasi;
use App\Models\ListLokasi;

class ItemLokasiSeederComplete extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping barang per jenis lokasi
        $itemMapping = [
            'kelas_regular' => [
                'Meja Siswa', 'Kursi Siswa', 'Meja Guru', 'Kursi Guru', 
                'Papan Tulis', 'Spidol Whiteboard', 'Penghapus Papan', 
                'Penggaris Panjang', 'Proyektor', 'Screen Proyektor', 
                'Speaker', 'Kabel HDMI', 'Extension Listrik', 'Lampu Neon', 
                'Kipas Angin', 'AC (Air Conditioner)', 'Kalender', 
                'Jam Dinding', 'Tempat Sampah', 'Sapu', 'Pel', 'Taplak Meja',
                'CCTV', 'Hand Sanitizer', 'Tissue', 'Gorden'
            ],
            'lab_komputer' => [
                'Komputer Desktop', 'Monitor LCD', 'Keyboard', 'Mouse', 'CPU',
                'Stabilizer', 'Switch Network', 'Kabel LAN', 'Router WiFi',
                'Printer', 'Scanner', 'UPS', 'Headset', 'Webcam',
                'Meja Guru', 'Kursi Guru', 'Meja Siswa', 'Kursi Siswa',
                'Papan Tulis', 'Spidol Whiteboard', 'Penghapus Papan',
                'Proyektor', 'Screen Proyektor', 'Speaker', 'AC (Air Conditioner)',
                'Lampu Neon', 'Extension Listrik', 'Tempat Sampah', 'CCTV',
                'Alarm Kebakaran', 'Alat Pemadam', 'Hand Sanitizer'
            ],
            'lab_ipa' => [
                'Mikroskop', 'Tabung Reaksi', 'Bunsen Burner', 'Gelas Kimia',
                'Pipet', 'Timbangan Digital', 'pH Meter', 'Termometer',
                'Erlenmeyer', 'Rak Tabung Reaksi', 'Kaca Pembesar', 'Penjepit',
                'Spatula', 'Mortar', 'Pestle', 'Lemari Asam', 'Meja Lab', 'Kursi Lab',
                'Papan Tulis', 'Spidol Whiteboard', 'Penghapus Papan',
                'AC (Air Conditioner)', 'Lampu Neon', 'Extension Listrik',
                'Tempat Sampah', 'CCTV', 'Alarm Kebakaran', 'Alat Pemadam',
                'Kotak P3K', 'Hand Sanitizer', 'Dispenser Air'
            ],
            'perpustakaan' => [
                'Rak Buku', 'Meja Baca', 'Kursi Baca', 'Katalog Buku',
                'Barcode Scanner', 'Komputer Katalog', 'Stamp Perpustakaan',
                'Kartu Anggota', 'Loker Tas', 'Papan Pengumuman',
                'Meja Guru', 'Kursi Guru', 'AC (Air Conditioner)', 'Lampu Neon',
                'Extension Listrik', 'Tempat Sampah', 'CCTV', 'Hand Sanitizer',
                'Dispenser Air', 'Galon Air', 'Tissue', 'Gorden', 'Tirai'
            ]
        ];

        // Ambil semua lokasi dan item
        $lokasis = Lokasi::all();
        $allItems = Item::all()->keyBy('nama_item');

        foreach ($lokasis as $lokasi) {
            $lokasiType = $this->determineLokasiType($lokasi->nama_lokasi);
            $itemsForThisLokasi = $itemMapping[$lokasiType] ?? $itemMapping['kelas_regular'];

            foreach ($itemsForThisLokasi as $itemName) {
                $item = $allItems->get($itemName);
                
                if ($item) {
                    // Cek apakah relasi sudah ada
                    $exists = ListLokasi::where('id_lokasi', $lokasi->id_lokasi)
                                      ->where('id_item', $item->id_item)
                                      ->exists();
                    
                    if (!$exists) {
                        ListLokasi::create([
                            'id_lokasi' => $lokasi->id_lokasi,
                            'id_item' => $item->id_item,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Tentukan jenis lokasi berdasarkan nama lokasi
     */
    private function determineLokasiType($namaLokasi): string
    {
        $namaLokasi = strtolower($namaLokasi);
        
        if (str_contains($namaLokasi, 'lab komputer') || str_contains($namaLokasi, 'computer lab')) {
            return 'lab_komputer';
        } elseif (str_contains($namaLokasi, 'lab ipa') || str_contains($namaLokasi, 'lab') && (str_contains($namaLokasi, 'kimia') || str_contains($namaLokasi, 'fisika') || str_contains($namaLokasi, 'biologi'))) {
            return 'lab_ipa';
        } elseif (str_contains($namaLokasi, 'perpustakaan') || str_contains($namaLokasi, 'library')) {
            return 'perpustakaan';
        } else {
            return 'kelas_regular';
        }
    }
}