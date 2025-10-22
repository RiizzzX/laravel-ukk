<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListLokasi extends Model
{
    protected $table = 'list_lokasi';
    protected $primaryKey = 'id_list';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['id_lokasi','id_item'];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }
}
