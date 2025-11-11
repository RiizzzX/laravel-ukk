<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lokasi;

class ShowLokasiItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lokasi:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan semua lokasi beserta items yang ada di dalamnya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DATA LOKASI DAN ITEMS ===');
        $this->line('');

        $lokasis = Lokasi::all();

        $this->info("📊 Total Lokasi: " . $lokasis->count());
        $this->line('');

        foreach ($lokasis as $lokasi) {
            $items = $lokasi->items ?? [];
            $totalItems = count($items);
            $totalBarang = array_sum(array_column($items, 'jumlah'));

            $this->warn("📍 {$lokasi->nama_lokasi} ({$lokasi->gedung})");
            $this->line("   Total jenis barang: {$totalItems}");
            $this->line("   Total jumlah barang: {$totalBarang}");
            
            if ($totalItems > 0) {
                $this->line("   Daftar barang:");
                
                // Buat tabel
                $tableData = [];
                foreach ($items as $item) {
                    $tableData[] = [
                        $item['nama_item'] ?? 'N/A',
                        $item['jumlah'] ?? 0,
                        $item['kondisi'] ?? 'N/A'
                    ];
                }
                
                $this->table(
                    ['Nama Item', 'Jumlah', 'Kondisi'],
                    $tableData
                );
            } else {
                $this->error("   ❌ Tidak ada items!");
            }
            
            $this->line('');
        }

        $this->info('✅ Selesai!');
    }
}
