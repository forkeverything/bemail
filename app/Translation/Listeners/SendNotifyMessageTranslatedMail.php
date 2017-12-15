<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\NotifyMessageTranslated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNotifyMessageTranslatedMail
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if(! $event->message->send_to_self) {
            // Send translation complete notification to sender
            $recipient = $event->message->is_reply ? $event->message->reply_sender_email : $event->message->user;
            Mail::to($recipient)->send(new NotifyMessageTranslated($event->message));
        }
    }
}
