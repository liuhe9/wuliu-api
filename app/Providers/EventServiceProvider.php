<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'App\Events\LogisticsStart' => [
            'App\Listeners\SendLogisticsStartNotification',
        ],
        'App\Events\LogisticsConfirm' => [
            'App\Listeners\SendLogisticsConfirmNotification',
        ],
        'App\Events\LogisticsInTransit' => [
            'App\Listeners\SendLogisticsInTransitNotification',
        ],
        'App\Events\LogisticsArrived' => [
            'App\Listeners\SendLogisticsArrivedNotification',
        ],
        'App\Events\LogisticsFinished' => [
            'App\Listeners\SendLogisticsFinishedNotification',
        ],

        'App\Events\LogisticsSetDrivers' => [
            'App\Listeners\SendLogisticsSetDriversNotification',
        ],
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
