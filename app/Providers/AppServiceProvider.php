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

    public function boot()
    {
        // ... existing code

        // bind alias 'verified.email' to the middleware instance
        $this->app->singleton('verified.email', function ($app) {
            return $app->make(\App\Http\Middleware\EnsureEmailIsVerified::class);
        });
    }
}
