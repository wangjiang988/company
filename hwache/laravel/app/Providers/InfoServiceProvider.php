<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class InfoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer(
            '_layout.base_dealercenter','\App\Http\Controllers\User\BaseController'
        );
    }

    public function register()
    {
        //
    }
}