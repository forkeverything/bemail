<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\MessageTranslatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMessageTranslatedNotification implements ShouldQueue
{

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
            Mail::to($event->message->sender_email)->send(new MessageTranslatedNotification($event->message));
        }
    }
}
