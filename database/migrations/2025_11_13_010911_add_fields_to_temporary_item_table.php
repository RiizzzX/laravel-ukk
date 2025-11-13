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
            // Tambah id_user untuk langsung tahu siapa yang membuat temporary item
            if (!Schema::hasColumn('temporary_item', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('id_temporary');
                $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            }
            
            // Tambah deskripsi untuk pengaduan temporary
            if (!Schema::hasColumn('temporary_item', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('lokasi_barang_baru');
            }
            
            // Tambah foto untuk bukti temporary item
            if (!Schema::hasColumn('temporary_item', 'foto')) {
                $table->string('foto')->nullable()->after('deskripsi');
            }
            
            // Update status jika belum ada atau perlu diubah
            if (!Schema::hasColumn('temporary_item', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('foto');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temporary_item', function (Blueprint $table) {
            if (Schema::hasColumn('temporary_item', 'id_user')) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            }
            
            if (Schema::hasColumn('temporary_item', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            
            if (Schema::hasColumn('temporary_item', 'foto')) {
                $table->dropColumn('foto');
            }
            
            // Note: status sudah ada di migration sebelumnya, jadi tidak drop
        });
    }
};
