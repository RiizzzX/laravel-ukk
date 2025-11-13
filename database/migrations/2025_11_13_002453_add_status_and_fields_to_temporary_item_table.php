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
        Schema::table('temporary_item', function (Blueprint $table) {
            if (!Schema::hasColumn('temporary_item', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('lokasi_barang_baru');
            }
            if (!Schema::hasColumn('temporary_item', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('temporary_item', 'id_pengaduan')) {
                $table->unsignedBigInteger('id_pengaduan')->nullable()->after('alasan_penolakan');
                $table->foreign('id_pengaduan')->references('id_pengaduan')->on('pengaduan')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            if (Schema::hasColumn('temporary_item', 'id_pengaduan')) {
                $table->dropForeign(['id_pengaduan']);
                $table->dropColumn('id_pengaduan');
            }
            if (Schema::hasColumn('temporary_item', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
            if (Schema::hasColumn('temporary_item', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
