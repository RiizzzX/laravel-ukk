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
            // Cek apakah kolom temporary_item_id sudah ada, jika belum buat
            if (!Schema::hasColumn('pengaduan', 'temporary_item_id')) {
                $table->unsignedBigInteger('temporary_item_id')->nullable()->after('id_item');
            }
            
            // Buat foreign key yang benar ke tabel temporary_item
            $table->foreign('temporary_item_id')->references('id_temporary')->on('temporary_item')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropForeign(['temporary_item_id']);
        });
    }
};
