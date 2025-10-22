<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id_item');  // PK
            $table->string('nama_item');
            $table->unsignedBigInteger('id_lokasi'); // FK ke lokasi
            $table->timestamps();

            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('items');
    }
};
