<?php

namespace App\Payments\Listeners;

use App\Payments\Events\CustomerSubscriptionDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelSubscription
{

    /**
     * Handle the event.
     *
     * @param  CustomerSubscriptionDeleted  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->subscription()->cancel();
    }
}
