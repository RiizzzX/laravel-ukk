<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom status dari enum lama ke enum baru
        DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('pending','diproses','selesai','ditolak') DEFAULT 'pending'");
        
        // Update data lama 'proses' menjadi 'diproses' jika ada
        DB::statement("UPDATE pengaduan SET status = 'diproses' WHERE status = 'proses'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE pengaduan MODIFY COLUMN status ENUM('pending','proses','selesai') DEFAULT 'pending'");
    }
};
