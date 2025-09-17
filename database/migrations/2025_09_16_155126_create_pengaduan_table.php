<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->bigIncrements('id_pengaduan'); // PK
            $table->unsignedBigInteger('id_user');    // FK ke users
            $table->unsignedBigInteger('id_petugas')->nullable(); // FK ke petugas
            $table->unsignedBigInteger('id_item');    // FK ke items
            $table->text('deskripsi');
            $table->enum('status',['pending','proses','selesai'])->default('pending');
            $table->timestamps();
 $table->string('foto')->nullable(); // ✅ kolom foto untuk bukti
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->nullOnDelete();
            $table->foreign('id_item')->references('id_item')->on('items')->cascadeOnDelete();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengaduan');
    }
};
