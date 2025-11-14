<?php

namespace App\Observers;

use App\Models\Pengaduan;
use App\Models\Notifikasi;
use App\Models\Petugas; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class PengaduanObserver
{
    /**
     * Handle the Pengaduan "created" event.
     */
    public function created(Pengaduan $pengaduan): void
    {
        // 1. Notify USER (pembuat pengaduan) - Pengaduan berhasil dikirim
        Notifikasi::createNotification(
            $pengaduan->id_user,
            'pengaduan_terkirim',
            'Pengaduan Berhasil Dikirim',
            'Pengaduan #' . $pengaduan->id_pengaduan . ' Anda telah berhasil dikirim dan menunggu verifikasi admin.',
            route('pengaduan.index'),
            $pengaduan->id_pengaduan
        );
        
        // 2. Notify all ADMINS - Ada pengaduan baru
        Notifikasi::notifyAllAdmins(
            'pengaduan_baru',
            'Pengaduan Baru Masuk',
            'Pengaduan baru #' . $pengaduan->id_pengaduan . ' dari ' . $pengaduan->user->username . ' membutuhkan verifikasi.',
            route('admin.pengaduan.index'),
            $pengaduan->id_pengaduan
        );
    }

    /**
     * Handle the Pengaduan "updated" event.
     */
    public function updated(Pengaduan $pengaduan): void
    {
        // Check if status changed
        if ($pengaduan->isDirty('status')) {
            $oldStatus = $pengaduan->getOriginal('status');
            $newStatus = $pengaduan->status;
            
            // Status messages for user
            $statusMessages = [
                'diterima' => 'Pengaduan #' . $pengaduan->id_pengaduan . ' telah diterima dan sedang menunggu petugas.',
                'diproses' => 'Pengaduan #' . $pengaduan->id_pengaduan . ' sedang diproses oleh petugas.',
                'selesai' => 'Pengaduan #' . $pengaduan->id_pengaduan . ' telah selesai ditangani.',
                'ditolak' => 'Pengaduan #' . $pengaduan->id_pengaduan . ' ditolak. ' . ($pengaduan->tanggapan ? 'Alasan: ' . $pengaduan->tanggapan : '')
            ];
            
            // Notify USER about status change
            if (isset($statusMessages[$newStatus])) {
                Notifikasi::createNotification(
                    $pengaduan->id_user,
                    'pengaduan_' . $newStatus,
                    'Pengaduan ' . ucfirst($newStatus),
                    $statusMessages[$newStatus],
                    route('pengaduan.riwayat'),
                    $pengaduan->id_pengaduan
                );
            }
            
            // Notify PETUGAS when status changes to "diterima" (ready to be assigned)
            if ($newStatus === 'diterima') {
                Notifikasi::notifyAllPetugas(
                    'pengaduan_siap',
                    'Pengaduan Siap Ditangani',
                    'Pengaduan #' . $pengaduan->id_pengaduan . ' telah diterima admin dan siap untuk ditangani.',
                    route('petugas.pengaduan.index'),
                    $pengaduan->id_pengaduan
                );
            }
            
            // Notify ADMINS about status change
            Notifikasi::notifyAllAdmins(
                'pengaduan_status',
                'Status Pengaduan Diperbarui',
                'Pengaduan #' . $pengaduan->id_pengaduan . ' berubah dari "' . $oldStatus . '" ke "' . $newStatus . '"',
                route('admin.pengaduan.index'),
                $pengaduan->id_pengaduan
            );
        }
        
        // Check if petugas assigned (when admin assigns or petugas takes the task)
        if ($pengaduan->isDirty('id_petugas') && $pengaduan->id_petugas) {
            // Notify assigned PETUGAS
            // Ambil petugas dan user-nya
            $petugas = Petugas::with('user')->find($pengaduan->id_petugas);
            
            if ($petugas && $petugas->user && $petugas->user->id_user) {
                Log::info('PengaduanObserver: Notifying petugas user_id=' . $petugas->user->id_user);
                
                Notifikasi::createNotification(
                    $petugas->user->id_user,
                    'pengaduan_ditugaskan',
                    'Pengaduan Ditugaskan ke Anda',
                    'Anda ditugaskan untuk menangani pengaduan #' . $pengaduan->id_pengaduan,
                    route('petugas.pengaduan.index'),
                    $pengaduan->id_pengaduan
                );
            } else {
                Log::warning('PengaduanObserver: Cannot notify petugas - User not found for id_petugas: ' . $pengaduan->id_petugas);
            }
            
            // Notify USER that petugas has been assigned
            if ($pengaduan->id_user) {
                Notifikasi::createNotification(
                    $pengaduan->id_user,
                    'pengaduan_petugas_ditugaskan',
                    'Petugas Ditugaskan',
                    'Pengaduan #' . $pengaduan->id_pengaduan . ' Anda telah ditangani oleh petugas.',
                    route('pengaduan.riwayat'),
                    $pengaduan->id_pengaduan
                );
            }
        }
    }

    /**
     * Handle the Pengaduan "deleted" event.
     */
    public function deleted(Pengaduan $pengaduan): void
    {
        //
    }

    /**
     * Handle the Pengaduan "restored" event.
     */
    public function restored(Pengaduan $pengaduan): void
    {
        //
    }

    /**
     * Handle the Pengaduan "force deleted" event.
     */
    public function forceDeleted(Pengaduan $pengaduan): void
    {
        //
    }
}
