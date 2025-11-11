<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeederComplete extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Peralatan Kelas
            ['nama_item' => 'Meja Siswa'],
            ['nama_item' => 'Kursi Siswa'],
            ['nama_item' => 'Meja Guru'],
            ['nama_item' => 'Kursi Guru'],
            ['nama_item' => 'Papan Tulis'],
            ['nama_item' => 'Spidol Whiteboard'],
            ['nama_item' => 'Penghapus Papan'],
            ['nama_item' => 'Penggaris Panjang'],
            ['nama_item' => 'Proyektor'],
            ['nama_item' => 'Screen Proyektor'],
            ['nama_item' => 'Speaker'],
            ['nama_item' => 'Kabel HDMI'],
            ['nama_item' => 'Extension Listrik'],
            ['nama_item' => 'Lampu Neon'],
            ['nama_item' => 'Kipas Angin'],
            ['nama_item' => 'AC (Air Conditioner)'],
            ['nama_item' => 'Kalender'],
            ['nama_item' => 'Jam Dinding'],
            ['nama_item' => 'Tempat Sampah'],
            ['nama_item' => 'Sapu'],
            ['nama_item' => 'Pel'],
            ['nama_item' => 'Taplak Meja'],
            
            // Peralatan Lab Komputer
            ['nama_item' => 'Komputer Desktop'],
            ['nama_item' => 'Monitor LCD'],
            ['nama_item' => 'Keyboard'],
            ['nama_item' => 'Mouse'],
            ['nama_item' => 'CPU'],
            ['nama_item' => 'Stabilizer'],
            ['nama_item' => 'Switch Network'],
            ['nama_item' => 'Kabel LAN'],
            ['nama_item' => 'Router WiFi'],
            ['nama_item' => 'Printer'],
            ['nama_item' => 'Scanner'],
            ['nama_item' => 'UPS'],
            ['nama_item' => 'Headset'],
            ['nama_item' => 'Webcam'],
            
            // Peralatan Lab IPA
            ['nama_item' => 'Mikroskop'],
            ['nama_item' => 'Tabung Reaksi'],
            ['nama_item' => 'Bunsen Burner'],
            ['nama_item' => 'Gelas Kimia'],
            ['nama_item' => 'Pipet'],
            ['nama_item' => 'Timbangan Digital'],
            ['nama_item' => 'pH Meter'],
            ['nama_item' => 'Termometer'],
            ['nama_item' => 'Erlenmeyer'],
            ['nama_item' => 'Rak Tabung Reaksi'],
            ['nama_item' => 'Kaca Pembesar'],
            ['nama_item' => 'Penjepit'],
            ['nama_item' => 'Spatula'],
            ['nama_item' => 'Mortar'],
            ['nama_item' => 'Pestle'],
            ['nama_item' => 'Lemari Asam'],
            ['nama_item' => 'Meja Lab'],
            ['nama_item' => 'Kursi Lab'],
            
            // Peralatan Perpustakaan
            ['nama_item' => 'Rak Buku'],
            ['nama_item' => 'Meja Baca'],
            ['nama_item' => 'Kursi Baca'],
            ['nama_item' => 'Katalog Buku'],
            ['nama_item' => 'Barcode Scanner'],
            ['nama_item' => 'Komputer Katalog'],
            ['nama_item' => 'Stamp Perpustakaan'],
            ['nama_item' => 'Kartu Anggota'],
            ['nama_item' => 'Loker Tas'],
            ['nama_item' => 'Papan Pengumuman'],
            
            // Peralatan Umum Tambahan
            ['nama_item' => 'CCTV'],
            ['nama_item' => 'Alarm Kebakaran'],
            ['nama_item' => 'Alat Pemadam'],
            ['nama_item' => 'Kotak P3K'],
            ['nama_item' => 'Hand Sanitizer'],
            ['nama_item' => 'Dispenser Air'],
            ['nama_item' => 'Galon Air'],
            ['nama_item' => 'Tissue'],
            ['nama_item' => 'Gorden'],
            ['nama_item' => 'Tirai'],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate($item);
        }
    }
}