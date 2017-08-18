<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\OrderShipped' => [
            'App\Listeners\SendShipmentNotification',
        ],
        'App\Events\CheckBaojiaEvent' => [
            'App\Listeners\CheckBaojiaListener',
        ],
        'App\Events\AccountTooLowEvent' => [
            'App\Listeners\AccountTooLowListener',
        ],
        'App\Events\HcDailiAccountSavedEvent' => [
            'App\Listeners\HcDailiAccountSavedListener',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
