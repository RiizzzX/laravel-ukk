<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('petugas', function (Blueprint $table) {
            $table->bigIncrements('id_petugas');  // PK
            $table->unsignedBigInteger('id_user')->nullable();  // FK ke users
            $table->string('nama_petugas');
            $table->string('nama')->nullable();
            $table->string('gender')->nullable();
            $table->string('telp')->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('petugas');
    }
};
