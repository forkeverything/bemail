<?php

namespace App\Payments\Listeners;

use App\Payments\Events\CustomerSubscriptionDeleted;
use App\Payments\Plan;
use App\Payments\Subscription;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DowngradeSubscriptionToFreePlan
{

    /**
     * Handle the event.
     *
     * @param  CustomerSubscriptionDeleted  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->subscription()->swap(Plan::FREE);
    }

}
