<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        // Tambah Lokasi
        $labKomputer = Lokasi::firstOrCreate(['nama_lokasi' => 'Lab Komputer']);
        $kelas = Lokasi::firstOrCreate(['nama_lokasi' => 'Ruang Kelas']);

        // Item untuk Lab Komputer
        $itemsLabKomputer = [
            'Komputer',
            'Monitor',
            'Keyboard',
            'Mouse',
            'AC',
            'Proyektor',
            'Kursi Komputer',
            'Meja Komputer',
            'Switch Network',
            'Router',
            'Printer',
            'Papan Tulis',
        ];

        foreach ($itemsLabKomputer as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $labKomputer->id_lokasi
            ]);
        }

        // Item untuk Ruang Kelas
        $itemsKelas = [
            'Meja Siswa',
            'Kursi Siswa',
            'Meja Guru',
            'Kursi Guru',
            'Papan Tulis',
            'Spidol',
            'Penghapus',
            'Kipas Angin',
            'Lemari',
            'Rak Buku',
        ];

        foreach ($itemsKelas as $item) {
            Item::firstOrCreate([
                'nama_item' => $item,
                'id_lokasi' => $kelas->id_lokasi
            ]);
        }

        echo "Data lokasi dan item berhasil ditambahkan!\n";
    }
}
