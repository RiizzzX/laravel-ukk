<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah kolom items (JSON) ke tabel lokasi
        Schema::table('lokasi', function (Blueprint $table) {
            $table->json('items')->nullable()->after('gedung');
        });

        // Migrasi data dari list_lokasi ke kolom items di lokasi
        $lokasis = DB::table('lokasi')->get();
        
        foreach ($lokasis as $lokasi) {
            // Ambil semua item untuk lokasi ini
            $items = DB::table('list_lokasi')
                ->join('items', 'list_lokasi.id_item', '=', 'items.id_item')
                ->where('list_lokasi.id_lokasi', $lokasi->id_lokasi)
                ->select('items.nama_item', 'items.id_item')
                ->get()
                ->map(function($item) {
                    return [
                        'id_item' => $item->id_item,
                        'nama_item' => $item->nama_item,
                        'jumlah' => 1,
                        'kondisi' => 'Baik'
                    ];
                })
                ->toArray();

            // Update lokasi dengan items
            DB::table('lokasi')
                ->where('id_lokasi', $lokasi->id_lokasi)
                ->update(['items' => json_encode($items)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokasi', function (Blueprint $table) {
            $table->dropColumn('items');
        });
    }
};
