<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';     // pastikan sama dengan nama tabel di DB
    protected $primaryKey = 'id_petugas'; // karena PK kamu bukan 'id' tapi 'id_petugas'
    public $timestamps = true;

    protected $fillable = [
        'nama_petugas',
        'jabatan',
    ];
}
