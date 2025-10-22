<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            // Buat id_item nullable untuk fitur saran item
            $table->unsignedBigInteger('id_item')->nullable()->change();
            
            // Tambah kolom lokasi/id_lokasi jika belum ada
            if (!Schema::hasColumn('pengaduan', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('id_item');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            // Kembalikan id_item menjadi not null
            $table->unsignedBigInteger('id_item')->nullable(false)->change();
            
            // Hapus kolom lokasi
            if (Schema::hasColumn('pengaduan', 'lokasi')) {
                $table->dropColumn('lokasi');
            }
        });
    }
};
