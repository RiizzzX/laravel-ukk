<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryItem extends Model
{
    use HasFactory;

    protected $table = 'temporary_item';
    protected $primaryKey = 'id_temporary';
    
    protected $fillable = [
        'id_item',
        'id_pengaduan',
        'id_user', // Tambahan untuk langsung tahu siapa yang buat
        'nama_barang_baru',
        'lokasi_barang_baru',
        'status', // pending, approved, rejected
        'alasan_penolakan',
        'deskripsi', // Deskripsi pengaduan temporary
        'foto', // Foto bukti temporary item
    ];

    // Relation to Item (optional - jika ada item reference)
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    // Relation to Pengaduan (one-to-one)
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan', 'id_pengaduan');
    }
    
    // User yang membuat temporary item
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
