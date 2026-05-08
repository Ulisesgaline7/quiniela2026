<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // First registered user (id=1) is admin, or set is_admin column
        Gate::define('admin', function (User $user) {
            return $user->id === 1;
        });
    }
}
