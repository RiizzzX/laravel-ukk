<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user'; // pakai id_user bukan id
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Kolom yang bisa diisi mass assignment
     */
    protected $fillable = [
        'username',
        'name',
        'password',
        'role',
    ];

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    // app/Models/User.php
    public function petugas()
    {
        return $this->hasOne(Petugas::class, 'id_user', 'id_user');
    }

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
