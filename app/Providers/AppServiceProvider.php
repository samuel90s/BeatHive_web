<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\SoundEffect;
use App\Policies\SoundEffectPolicy;
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
        /**
         * Gate global: hanya admin (role == 1) yang boleh.
         * Sesuaikan jika field/aturanmu berbeda (mis. is_admin boolean).
         */
        Gate::define('admin-only', function (User $user) {
            return (int) ($user->role ?? 0) === 1;
        });
        
        /**
         * (Opsional) Superadmin bypass semua Gate.
         * Aktifkan kalau kamu punya role khusus, mis. 99.
         */
        // Gate::before(function (?User $user, string $ability) {
        //     if ($user && (int) ($user->role ?? 0) === 99) {
        //         return true;
        //     }
        //     return null;
        // });
    }
    protected $policies = [
        SoundEffect::class => SoundEffectPolicy::class,
    ];
}
