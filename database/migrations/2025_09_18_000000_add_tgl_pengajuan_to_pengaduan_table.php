<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTglPengajuanToPengaduanTable extends Migration
{
    public function up()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->timestamp('tgl_pengajuan')->nullable()->after('foto');
        });
    }

    public function down()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn('tgl_pengajuan');
        });
    }
}