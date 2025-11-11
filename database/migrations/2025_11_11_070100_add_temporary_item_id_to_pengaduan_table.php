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
            $table->unsignedBigInteger('temporary_item_id')->nullable()->after('id_item');
            $table->foreign('temporary_item_id')->references('id_temporary_item')->on('temporary_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropForeign(['temporary_item_id']);
            $table->dropColumn('temporary_item_id');
        });
    }
};
