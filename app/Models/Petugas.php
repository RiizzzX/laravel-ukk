<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',   // foreign key ke tabel users
        'nama_petugas',
        'gender',
        'telp',
        'jabatan',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke tabel pengaduan
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'id_petugas', 'id_petugas');
    }
}
