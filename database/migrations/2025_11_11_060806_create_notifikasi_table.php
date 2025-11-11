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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_user'); // User yang menerima notifikasi
            $table->string('tipe'); // 'pengaduan', 'user_baru', 'pengaduan_ditugaskan', 'pengaduan_selesai', dll
            $table->string('judul');
            $table->text('isi');
            $table->string('link')->nullable(); // Link untuk redirect
            $table->unsignedBigInteger('ref_id')->nullable(); // ID referensi (id_pengaduan, id_user, dll)
            $table->boolean('is_read')->default(false);
            $table->timestamps();
            
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->index(['id_user', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
