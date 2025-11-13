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
            // Untuk temporary item yang belum approved
            $table->string('nama_item_temporary')->nullable()->after('id_item');
            $table->text('deskripsi_item_temporary')->nullable()->after('nama_item_temporary');
            $table->string('alasan_penolakan_item')->nullable()->after('deskripsi_item_temporary');
            $table->timestamp('item_approved_at')->nullable()->after('alasan_penolakan_item');
            $table->unsignedBigInteger('item_approved_by')->nullable()->after('item_approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn([
                'nama_item_temporary',
                'deskripsi_item_temporary',
                'alasan_penolakan_item',
                'item_approved_at',
                'item_approved_by'
            ]);
        });
    }
};
