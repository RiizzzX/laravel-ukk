<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['nama_lokasi', 'gedung', 'items'];

    protected $casts = [
        'items' => 'array', // Cast JSON ke array
    ];

    // Many-to-many relationship dengan Item via list_lokasi (backward compatibility)
    public function itemsRelation()
    {
        return $this->belongsToMany(Item::class, 'list_lokasi', 'id_lokasi', 'id_item', 'id_lokasi', 'id_item');
    }

    // Untuk backward compatibility
    public function listLokasi()
    {
        return $this->hasMany(ListLokasi::class, 'id_lokasi', 'id_lokasi');
    }

    // Helper method untuk get items
    public function getItemsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    // Helper method untuk set items
    public function setItemsAttribute($value)
    {
        $this->attributes['items'] = json_encode($value);
    }
}
