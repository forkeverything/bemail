<?php

namespace App\Payment\Listeners;

use App\Payment\Events\CustomerSubscriptionDeleted;
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
