<?php

namespace App\Listeners;

use App\Payments\Plan;
use App\Payments\Subscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeUserToFreePlan
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle($event)
    {
        $event->user->newSubscription(Subscription::MAIN, Plan::FREE)->create();
    }
}
