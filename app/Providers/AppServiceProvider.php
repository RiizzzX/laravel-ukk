<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\User;
use App\Models\Pengaduan;
use App\Observers\UserObserver;
use App\Observers\PengaduanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        User::observe(UserObserver::class);
        Pengaduan::observe(PengaduanObserver::class);
        
        // Set custom pagination view
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');
        
        // Share pengaduanBaru count ke semua view untuk notifikasi sidebar
        view()->composer('*', function ($view) {
            if (auth()->check() && auth()->user()->role === 'petugas') {
                $petugas = \App\Models\Petugas::where('id_user', auth()->id())->first();
                
                if ($petugas) {
                    // Hitung pengaduan baru untuk sidebar (tetap muncul sampai pengaduan diproses/diambil)
                    $notifPengaduanBaru = \App\Models\Pengaduan::where('status', 'diterima')
                                                  ->whereNull('id_petugas')
                                                  ->count();
                    
                    $view->with('notifPengaduanBaru', $notifPengaduanBaru);
                }
            }
        });
    }
}
