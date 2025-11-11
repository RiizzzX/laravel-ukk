<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lokasi;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lokasis = [
            ['nama_lokasi' => 'Kelas X-1', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Kelas X-2', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Kelas X-3', 'gedung' => 'Gedung A'],
            ['nama_lokasi' => 'Kelas XI-1', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Kelas XI-2', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Kelas XI-3', 'gedung' => 'Gedung B'],
            ['nama_lokasi' => 'Kelas XII-1', 'gedung' => 'Gedung C'],
            ['nama_lokasi' => 'Kelas XII-2', 'gedung' => 'Gedung C'],
            ['nama_lokasi' => 'Kelas XII-3', 'gedung' => 'Gedung C'],
            ['nama_lokasi' => 'Lab Komputer', 'gedung' => 'Gedung D'],
            ['nama_lokasi' => 'Lab IPA', 'gedung' => 'Gedung D'],
            ['nama_lokasi' => 'Perpustakaan', 'gedung' => 'Gedung E'],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::firstOrCreate($lokasi);
        }
    }
}