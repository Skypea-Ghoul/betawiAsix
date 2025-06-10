<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class FilamentAuthProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            // Jika user sudah login dan role = 'customer', abort 403
            if (Auth::check() && Auth::user()->role === 'customer') {
                abort(403, 'Anda tidak memiliki akses ke Dashboard Admin');
            }
        });
    }
}
