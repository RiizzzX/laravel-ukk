<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiWithItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Items untuk kelas regular
        $itemsKelasRegular = [
            ['nama_item' => 'Meja Siswa', 'jumlah' => 20, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Siswa', 'jumlah' => 40, 'kondisi' => 'Baik'],
            ['nama_item' => 'Meja Guru', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Guru', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Papan Tulis', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Spidol', 'jumlah' => 5, 'kondisi' => 'Baik'],
            ['nama_item' => 'Penghapus Papan', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Proyektor', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Screen Proyektor', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Speaker', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'AC', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kipas Angin', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Lampu LED', 'jumlah' => 8, 'kondisi' => 'Baik'],
            ['nama_item' => 'Jam Dinding', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Tempat Sampah', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Sapu', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Pel', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'CCTV', 'jumlah' => 1, 'kondisi' => 'Baik'],
        ];

        // Items untuk lab komputer
        $itemsLabKomputer = [
            ['nama_item' => 'Komputer Desktop', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Monitor LCD', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Keyboard', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Mouse', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Meja Komputer', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Putar', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Stabilizer', 'jumlah' => 10, 'kondisi' => 'Baik'],
            ['nama_item' => 'Switch Network', 'jumlah' => 3, 'kondisi' => 'Baik'],
            ['nama_item' => 'Router WiFi', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'Printer', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Scanner', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'UPS', 'jumlah' => 5, 'kondisi' => 'Baik'],
            ['nama_item' => 'Proyektor', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'AC', 'jumlah' => 3, 'kondisi' => 'Baik'],
            ['nama_item' => 'Lampu LED', 'jumlah' => 12, 'kondisi' => 'Baik'],
            ['nama_item' => 'Papan Tulis', 'jumlah' => 1, 'kondisi' => 'Baik'],
            ['nama_item' => 'CCTV', 'jumlah' => 2, 'kondisi' => 'Baik'],
        ];

        // Items untuk lab IPA
        $itemsLabIPA = [
            ['nama_item' => 'Mikroskop', 'jumlah' => 15, 'kondisi' => 'Baik'],
            ['nama_item' => 'Tabung Reaksi', 'jumlah' => 50, 'kondisi' => 'Baik'],
            ['nama_item' => 'Gelas Kimia', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Bunsen Burner', 'jumlah' => 10, 'kondisi' => 'Baik'],
            ['nama_item' => 'Pipet', 'jumlah' => 30, 'kondisi' => 'Baik'],
            ['nama_item' => 'Erlenmeyer', 'jumlah' => 20, 'kondisi' => 'Baik'],
            ['nama_item' => 'Timbangan Digital', 'jumlah' => 5, 'kondisi' => 'Baik'],
            ['nama_item' => 'pH Meter', 'jumlah' => 3, 'kondisi' => 'Baik'],
            ['nama_item' => 'Termometer', 'jumlah' => 10, 'kondisi' => 'Baik'],
            ['nama_item' => 'Rak Tabung', 'jumlah' => 15, 'kondisi' => 'Baik'],
            ['nama_item' => 'Meja Lab', 'jumlah' => 10, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Lab', 'jumlah' => 40, 'kondisi' => 'Baik'],
            ['nama_item' => 'Lemari Asam', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Wastafel', 'jumlah' => 4, 'kondisi' => 'Baik'],
            ['nama_item' => 'Alat Pemadam', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kotak P3K', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'AC', 'jumlah' => 2, 'kondisi' => 'Baik'],
        ];

        // Items untuk perpustakaan
        $itemsPerpustakaan = [
            ['nama_item' => 'Rak Buku', 'jumlah' => 20, 'kondisi' => 'Baik'],
            ['nama_item' => 'Meja Baca', 'jumlah' => 15, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Baca', 'jumlah' => 60, 'kondisi' => 'Baik'],
            ['nama_item' => 'Komputer Katalog', 'jumlah' => 3, 'kondisi' => 'Baik'],
            ['nama_item' => 'Barcode Scanner', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Meja Pustakawan', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Kursi Pustakawan', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'Loker Tas', 'jumlah' => 40, 'kondisi' => 'Baik'],
            ['nama_item' => 'Papan Pengumuman', 'jumlah' => 2, 'kondisi' => 'Baik'],
            ['nama_item' => 'AC', 'jumlah' => 3, 'kondisi' => 'Baik'],
            ['nama_item' => 'Lampu LED', 'jumlah' => 16, 'kondisi' => 'Baik'],
            ['nama_item' => 'CCTV', 'jumlah' => 2, 'kondisi' => 'Baik'],
        ];

        $lokasis = [
            // Kelas X
            ['nama_lokasi' => 'Kelas X-1', 'gedung' => 'Gedung A', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas X-2', 'gedung' => 'Gedung A', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas X-3', 'gedung' => 'Gedung A', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas X-4', 'gedung' => 'Gedung A', 'items' => $itemsKelasRegular],
            
            // Kelas XI
            ['nama_lokasi' => 'Kelas XI-1', 'gedung' => 'Gedung B', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XI-2', 'gedung' => 'Gedung B', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XI-3', 'gedung' => 'Gedung B', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XI-4', 'gedung' => 'Gedung B', 'items' => $itemsKelasRegular],
            
            // Kelas XII
            ['nama_lokasi' => 'Kelas XII-1', 'gedung' => 'Gedung C', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XII-2', 'gedung' => 'Gedung C', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XII-3', 'gedung' => 'Gedung C', 'items' => $itemsKelasRegular],
            ['nama_lokasi' => 'Kelas XII-4', 'gedung' => 'Gedung C', 'items' => $itemsKelasRegular],
            
            // Lab
            ['nama_lokasi' => 'Lab Komputer 1', 'gedung' => 'Gedung D', 'items' => $itemsLabKomputer],
            ['nama_lokasi' => 'Lab Komputer 2', 'gedung' => 'Gedung D', 'items' => $itemsLabKomputer],
            ['nama_lokasi' => 'Lab IPA', 'gedung' => 'Gedung D', 'items' => $itemsLabIPA],
            
            // Fasilitas Lain
            ['nama_lokasi' => 'Perpustakaan', 'gedung' => 'Gedung E', 'items' => $itemsPerpustakaan],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::updateOrCreate(
                ['nama_lokasi' => $lokasi['nama_lokasi']],
                $lokasi
            );
        }

        $this->command->info('✅ Seeder berhasil! Semua lokasi telah terisi dengan items di dalamnya.');
    }
}