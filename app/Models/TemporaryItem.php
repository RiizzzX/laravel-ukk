<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryItem extends Model
{
    use HasFactory;

    protected $table = 'temporary_items';
    protected $primaryKey = 'id_temporary_item';

    protected $fillable = [
        'nama_item',
        'deskripsi',
        'id_lokasi',
        'created_by',
        'status',
        'alasan_penolakan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Relasi ke User (pembuat)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    /**
     * Relasi ke User (admin yang approve/reject)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id_user');
    }

    /**
     * Relasi ke Lokasi
     */
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    /**
     * Scope untuk pending items
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk approved items
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk rejected items
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
