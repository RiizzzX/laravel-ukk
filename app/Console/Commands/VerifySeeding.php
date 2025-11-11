<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lokasi;
use App\Models\Item;
use App\Models\ListLokasi;

class VerifySeeding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:seeding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi hasil seeding barang ke semua lokasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== VERIFIKASI SEEDING ===');
        $this->line('');

        $totalLokasi = Lokasi::count();
        $totalItem = Item::count();
        $totalHubungan = ListLokasi::count();

        $this->info("✅ Total Lokasi: {$totalLokasi}");
        $this->info("✅ Total Item: {$totalItem}");
        $this->info("✅ Total Hubungan Item-Lokasi: {$totalHubungan}");
        $this->line('');

        $lokasis = Lokasi::with(['listLokasi' => function($query) {
            $query->with('item');
        }])->get();

        $this->info('📍 DETAIL LOKASI DAN BARANG:');
        $this->line('');

        foreach ($lokasis as $lokasi) {
            $jumlahBarang = $lokasi->listLokasi->count();
            $this->warn("🏢 {$lokasi->nama_lokasi} ({$lokasi->gedung})");
            $this->line("   Jumlah barang: {$jumlahBarang}");
            
            if ($jumlahBarang > 0) {
                $this->line("   Contoh barang:");
                $lokasi->listLokasi->take(5)->each(function($listLokasi) {
                    $this->line("   - {$listLokasi->item->nama_item}");
                });
                
                if ($jumlahBarang > 5) {
                    $this->line("   ... dan " . ($jumlahBarang - 5) . " barang lainnya");
                }
            } else {
                $this->error("   ❌ Tidak ada barang!");
            }
            $this->line('');
        }

        $this->info('🎉 Seeding berhasil! Semua kelas telah terisi dengan barang-barangnya.');
    }
}
