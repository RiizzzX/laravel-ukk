<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Item;
use App\Models\Lokasi;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $primaryKey = 'id_pengaduan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_pengaduan',
        'deskripsi',
        'id_lokasi', // <-- ganti dari 'lokasi' ke 'id_lokasi'
        'foto',
        'status',
        'id_user',
        'id_petugas',
        'id_item',
        'tgl_pengajuan',
        'tgl_selesai',
        'saran_petugas'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }
}
