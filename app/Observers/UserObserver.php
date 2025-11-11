<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Notifikasi;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Notify all admins when new user registers
        if ($user->role === 'pengguna') {
            Notifikasi::notifyAllAdmins(
                'user_baru',
                'User Baru Terdaftar',
                'User baru "' . $user->username . '" telah mendaftar di sistem.',
                route('admin.users.index'),
                $user->id_user
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
