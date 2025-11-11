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
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('is_temporary')->default(false)->after('deskripsi');
            $table->boolean('is_approved')->default(false)->after('is_temporary');
            $table->unsignedBigInteger('created_by')->nullable()->after('is_approved');
            
            $table->foreign('created_by')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['is_temporary', 'is_approved', 'created_by']);
        });
    }
};
