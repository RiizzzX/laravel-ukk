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
        Schema::table('pengaduan', function (Blueprint $table) {
            // Cek dan tambah kolom yang belum ada
            if (!Schema::hasColumn('pengaduan', 'tanggal_diterima')) {
                $table->timestamp('tanggal_diterima')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pengaduan', 'tanggal_selesai')) {
                $table->timestamp('tanggal_selesai')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pengaduan', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('status');
            }
            if (!Schema::hasColumn('pengaduan', 'catatan_petugas')) {
                $table->text('catatan_petugas')->nullable()->after('status');
            }
        });
        
        // Update enum status (dilakukan terpisah)
        DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('pending', 'diterima', 'ditolak', 'diproses', 'selesai') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            if (Schema::hasColumn('pengaduan', 'tanggal_diterima')) {
                $table->dropColumn('tanggal_diterima');
            }
            if (Schema::hasColumn('pengaduan', 'tanggal_selesai')) {
                $table->dropColumn('tanggal_selesai');
            }
            if (Schema::hasColumn('pengaduan', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
            if (Schema::hasColumn('pengaduan', 'catatan_petugas')) {
                $table->dropColumn('catatan_petugas');
            }
        });
    }
};
