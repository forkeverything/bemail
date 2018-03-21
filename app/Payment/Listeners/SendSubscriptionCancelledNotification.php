<?php

namespace App\Payment\Listeners;

use App\Payment\Events\CustomerSubscriptionDeleted;
use App\Payment\Mail\SubscriptionCancelledMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionCancelledNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  CustomerSubscriptionDeleted  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user)->send(new SubscriptionCancelledMail($event->user));
    }
}
