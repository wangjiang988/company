<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InfoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            ['_layout.base_dealercenter','_layout.base_dealer_v2'],'\App\Http\Controllers\Dealer\BaseController'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
