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

  protected $fillable = ['nama_item','deskripsi','foto','is_temporary','is_approved','created_by'];

  protected $casts = [
    'is_temporary' => 'boolean',
    'is_approved' => 'boolean',
  ];

  // Many-to-many relationship dengan Lokasi via list_lokasi
  public function lokasis()
  {
    return $this->belongsToMany(Lokasi::class, 'list_lokasi', 'id_item', 'id_lokasi', 'id_item', 'id_lokasi');
  }

  // Untuk backward compatibility
  public function listLokasi()
  {
    return $this->hasMany(ListLokasi::class, 'id_item', 'id_item');
  }

  // Creator user
  public function creator()
  {
    return $this->belongsTo(User::class, 'created_by', 'id_user');
  }
}
