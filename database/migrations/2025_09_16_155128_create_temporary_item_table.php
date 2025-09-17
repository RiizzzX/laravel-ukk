<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('temporary_item', function (Blueprint $table) {
            $table->id('id_temp');
            $table->string('nama_item');
            $table->integer('jumlah')->default(0);
            $table->timestamps();
        });

        // Default temporary data
        DB::table('temporary_item')->insert([
            ['nama_item' => 'Whiteboard', 'jumlah' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['nama_item' => 'Spidol', 'jumlah' => 20, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('temporary_item');
    }
};
