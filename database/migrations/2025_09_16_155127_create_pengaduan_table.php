<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id('id_pengaduan');
            $table->foreignId('id_user')->constrained('users','id')->cascadeOnDelete();
            $table->foreignId('id_petugas')->nullable()->constrained('petugas','id_petugas')->nullOnDelete();
            $table->foreignId('id_item')->constrained('items','id_item')->cascadeOnDelete();
            $table->text('deskripsi');
            $table->enum('status',['pending','proses','selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pengaduan');
    }
};
