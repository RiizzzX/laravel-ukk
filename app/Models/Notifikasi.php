<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    
    protected $fillable = [
        'id_user',
        'tipe',
        'judul',
        'isi',
        'link',
        'ref_id',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper method untuk membuat notifikasi
    public static function createNotification($userId, $tipe, $judul, $isi, $link = null, $refId = null)
    {
        // Verify user exists before creating notification
        $userExists = User::where('id_user', $userId)->exists();
        
        if (!$userExists) {
            \Log::warning("Notifikasi: Cannot create notification for non-existent user_id: {$userId}");
            return null;
        }
        
        return self::create([
            'id_user' => $userId,
            'tipe' => $tipe,
            'judul' => $judul,
            'isi' => $isi,
            'link' => $link,
            'ref_id' => $refId,
        ]);
    }

    // Notifikasi untuk semua admin
    public static function notifyAllAdmins($tipe, $judul, $isi, $link = null, $refId = null)
    {
        $admins = User::where('role', 'admin')->get();
        
        foreach ($admins as $admin) {
            self::createNotification($admin->id_user, $tipe, $judul, $isi, $link, $refId);
        }
    }

    // Notifikasi untuk semua petugas
    public static function notifyAllPetugas($tipe, $judul, $isi, $link = null, $refId = null)
    {
        $petugas = User::where('role', 'petugas')->get();
        
        foreach ($petugas as $p) {
            self::createNotification($p->id_user, $tipe, $judul, $isi, $link, $refId);
        }
    }
}
