<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::define('administrator', function(User $user){
            return $user->role == 'administrator';
        });

        Gate::define('manager', function(User $user){
            return $user->role == 'manager';
        });

        Gate::define('operation', function(User $user){
            return $user->role == 'operation';
        });

        Gate::define('driver', function(User $user){
            return $user->role == 'driver';
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
