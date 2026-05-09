<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\UrlGenerator;
use Turso\Http\Laravel\LibSQLHttpServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists(LibSQLHttpServiceProvider::class)) {
            $this->app->register(LibSQLHttpServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
    {
        Model::unguard(); // Disable need of using $fillables

        // Enforce HTTPS on all pages
        if (env('APP_ENV') == 'production') {
            $url->forceScheme('https');
        }
    }
}
