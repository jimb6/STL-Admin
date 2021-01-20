<?php

namespace App\Providers;

use App\Events\BetTransactionAdded;
use App\Events\NewDeviceAdded;
use App\Models\WinningBet;
use App\Observers\WinningBetObserver;
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
            NewDeviceAdded::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
        WinningBet::observe(WinningBetObserver::class);
    }
}
