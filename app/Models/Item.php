<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';           // nama tabel
    protected $primaryKey = 'id_item';    // PK tabel items
    public $timestamps = true;            // aktifkan created_at & updated_at

    protected $fillable = [
        'nama_item',
        'kategori',
        'jumlah',
        'kondisi',
    ];
}
