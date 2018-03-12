<?php

namespace App\Payments\Listeners;

use App\Payments\Events\CustomerSubscriptionDeleted;
use App\Payments\Mail\PlanDowngradedMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendDowngradedToFreePlanNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  CustomerSubscriptionDeleted  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user)->send(new PlanDowngradedMail($event->user));
    }
}
