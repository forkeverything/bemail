<?php

namespace App\Payment\Listeners;

use App\Payment\Events\FailedChargingUserForMessage;
use App\Payment\Mail\ChargeFailureNotificationForOwnerSentMessage;
use App\Payment\Mail\ChargeFailureNotificationForReplyOwner;
use App\Payment\Mail\ChargeFailureNotificationForReplySender;
use App\Translation\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendChargeFailureNotifications implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  FailedChargingUserForMessage  $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->message->isReply() && ! $event->message->senderIsTheOwner()) {
            Mail::to($event->message->owner)->send(new ChargeFailureNotificationForReplyOwner($event->message));
            Mail::to($event->message->sender_email)->send(new ChargeFailureNotificationForReplySender($event->message));
        } else {
            // Notify owner that message won't be sent
            Mail::to($event->message->owner)->send(new ChargeFailureNotificationForOwnerSentMessage($event->message));
        }
    }


}
