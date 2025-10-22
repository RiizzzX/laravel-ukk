<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

      protected $table = 'items';
    protected $primaryKey = 'id_item';
    public $incrementing = true;
    protected $keyType = 'int';

  protected $fillable = ['nama_item','id_lokasi','deskripsi','foto'];

  // Relasi ke Lokasi
  public function lokasi()
  {
    return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
  }
}
