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
            // Tambah field tipe_pengaduan untuk membedakan pengaduan normal dan temporary
            $table->enum('tipe_pengaduan', ['normal', 'temporary'])->default('normal')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn('tipe_pengaduan');
        });
    }
};
