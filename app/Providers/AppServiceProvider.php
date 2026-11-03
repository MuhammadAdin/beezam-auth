<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Gunakan Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();

        // Share current user ke semua view
        View::composer('*', function ($view) {
            if (session()->has('user_id')) {
                $user = User::find(session('user_id'));
                $view->with('currentUser', $user);
            } else {
                $view->with('currentUser', null);
            }
        });
    }
}