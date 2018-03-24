<?php

namespace App\Payment\Listeners;

use App\Payment\Events\FailedChargingUserForMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUsersThatMessageNotSentDueToChargeFailure
{

    /**
     * Handle the event.
     *
     * @param  FailedChargingUserForMessage  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->message->isReply()) {
            // Notify owner that a reply could not be sent because charge failed
            // Notify sender that the reply was not sent because the owner could not be charged
        } else {
            // Notify owner that message won't be sent
        }
    }

}
