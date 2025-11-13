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
        'foto_penyelesaian', // foto bukti penyelesaian dari petugas
        'is_read', // status notifikasi sudah dibaca user
        'status',
        'tipe_pengaduan', // normal atau temporary
        'id_user',
        'id_petugas',
        'id_item',
        'temporary_item_id', // relasi ke temporary_items
        'tgl_pengajuan',
        'tgl_selesai',
        'saran_petugas',
        'tanggal_diterima',
        'tanggal_selesai',
        'catatan_admin',
        'catatan_petugas'
    ];

    protected $casts = [
        'tanggal_diterima' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    // Scope untuk filter berdasarkan status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePendingItem($query)
    {
        return $query->where('status', 'pending_item');
    }

    public function scopeDiterima($query)
    {
        return $query->where('status', 'diterima');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status', 'diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeRiwayat($query)
    {
        return $query->whereIn('status', ['ditolak', 'selesai']);
    }

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
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    // Relasi ke temporary item
    public function temporaryItem()
    {
        return $this->belongsTo(TemporaryItem::class, 'temporary_item_id', 'id_temporary');
    }
}
