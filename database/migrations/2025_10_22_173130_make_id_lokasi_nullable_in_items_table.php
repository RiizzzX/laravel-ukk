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
            // Cek apakah kolom sudah ada, jika tidak tambahkan
            if (!Schema::hasColumn('items', 'id_lokasi')) {
                $table->unsignedBigInteger('id_lokasi')->nullable()->after('nama_item');
                $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('set null');
            } else {
                $table->unsignedBigInteger('id_lokasi')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'id_lokasi')) {
                $table->dropForeign(['id_lokasi']);
                $table->dropColumn('id_lokasi');
            }
        });
    }
};
