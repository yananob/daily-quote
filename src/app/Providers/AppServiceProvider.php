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

        // $this->__registerStoreDatabaseFunction();
        // $this->__retrieveDatabase();
        // // }
    }

    // private function __registerStoreDatabaseFunction(): void
    // {
    //     pcntl_async_signals(true);
    //     pcntl_signal(SIGTERM, function () {
    //         print("Received SIGTERM\n");
    //         print("Storing database\n");
    //         $this->__storeDatabase();
    //         print("Database stored.\n");
    //         exit;
    //     });
    //     printf("Registered event.\n");
    // }
}
