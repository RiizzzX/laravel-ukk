<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';           // nama tabel di database
    protected $primaryKey = 'id_lokasi';   // primary key tabel
    public $timestamps = true;             // ada kolom created_at & updated_at

    protected $fillable = [
        'nama_lokasi',
        'deskripsi',
    ];
}
