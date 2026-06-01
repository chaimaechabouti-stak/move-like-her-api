<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        if (env('VERCEL') || env('NOW_REGION')) {
        \Illuminate\Support\Facades\Artisan::command('migrate --force', function () {
            $this->info('Migrations bypassées sur Vercel pour protéger vos données phpMyAdmin !');
        });
    }
    }
}
