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
        'lokasi', // kolom lokasi menyimpan id_lokasi
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

    // Relasi lokasi menggunakan kolom 'lokasi' yang berisi id_lokasi
    public function lokasiRelation()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi', 'id_lokasi');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id_user');
    }
}
