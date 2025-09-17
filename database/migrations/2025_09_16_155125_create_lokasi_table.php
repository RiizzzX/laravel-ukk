<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id('id_lokasi');
            $table->string('nama_lokasi');
            $table->timestamps();
        });

        // Default lokasi
        DB::table('lokasi')->insert([
            ['nama_lokasi' => 'Ruang Kelas 1', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Lab Komputer', 'created_at' => now(), 'updated_at' => now()],
            ['nama_lokasi' => 'Perpustakaan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('lokasi');
    }
};
