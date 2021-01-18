<?php

namespace App\Listeners;

use App\Events\WiningBetPublishingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveWinningBetsIntoDatabase
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WiningBetPublishingEvent  $event
     * @return void
     */
    public function handle(WiningBetPublishingEvent $event)
    {
        //
    }
}
