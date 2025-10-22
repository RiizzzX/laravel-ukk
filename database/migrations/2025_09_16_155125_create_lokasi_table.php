<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->bigIncrements('id_lokasi');  // PK
            $table->string('nama_lokasi');
            $table->string('gedung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lokasi');
    }
};
