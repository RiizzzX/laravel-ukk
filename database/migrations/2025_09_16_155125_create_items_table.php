<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('items', function (Blueprint $table) {
            $table->id('id_item');
            $table->string('nama_item');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        // Default items
        DB::table('items')->insert([
            ['nama_item' => 'Proyektor', 'stok' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['nama_item' => 'Laptop', 'stok' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['nama_item' => 'Kursi', 'stok' => 50, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('items');
    }
};
