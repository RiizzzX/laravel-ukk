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
        Schema::create('temporary_items', function (Blueprint $table) {
            $table->id('id_temporary_item');
            $table->string('nama_item');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('id_lokasi'); // Lokasi yang diminta user
            $table->unsignedBigInteger('created_by'); // User yang request
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable(); // Jika ditolak
            $table->unsignedBigInteger('approved_by')->nullable(); // Admin yang approve/reject
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_lokasi')->references('id_lokasi')->on('lokasi')->onDelete('cascade');
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_items');
    }
};
