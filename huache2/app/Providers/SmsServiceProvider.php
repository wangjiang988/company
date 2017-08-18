<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * 启用延迟加载.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $sms = ucfirst(config('sms.sms'));
        $this->app->singleton('App\Core\Contracts\Sms\Sms', function() use ($sms) {
            return app('App\\Com\\Hwache\\Sms\\'.$sms);
        });
    }

    /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Core\Contracts\Sms\Sms'];
    }
}
