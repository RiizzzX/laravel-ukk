<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('list_lokasi', function (Blueprint $table) {
            $table->id('id_list');
            $table->foreignId('id_lokasi')->constrained('lokasi','id_lokasi')->cascadeOnDelete();
            $table->foreignId('id_item')->constrained('items','id_item')->cascadeOnDelete();
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('list_lokasi');
    }
};
