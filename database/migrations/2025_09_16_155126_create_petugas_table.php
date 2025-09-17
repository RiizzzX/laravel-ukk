<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('petugas', function (Blueprint $table) {
            $table->id('id_petugas');
            $table->string('nama_petugas');
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });

        // Default petugas
        DB::table('petugas')->insert([
            ['nama_petugas' => 'Budi Santoso', 'jabatan' => 'Teknisi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_petugas' => 'Siti Aminah', 'jabatan' => 'Staff Sarpras', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('petugas');
    }
};
