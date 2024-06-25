<?php

namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        // print("env: " . env('APP_ENV') . "\n");
        if (env('APP_ENV') === 'production') {
            $url->forceScheme('https');
        }
    }
}
