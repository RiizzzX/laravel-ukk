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
        Schema::table('items', function (Blueprint $table) {
            // Hapus kolom lokasi_id karena sudah ada id_lokasi
            if (Schema::hasColumn('items', 'lokasi_id')) {
                $table->dropForeign(['lokasi_id']);
                $table->dropColumn('lokasi_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            // Restore lokasi_id jika rollback
            if (!Schema::hasColumn('items', 'lokasi_id')) {
                $table->unsignedBigInteger('lokasi_id')->after('id_lokasi');
                $table->foreign('lokasi_id')->references('id_lokasi')->on('lokasi');
            }
        });
    }
};
