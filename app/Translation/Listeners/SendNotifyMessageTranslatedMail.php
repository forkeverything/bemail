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
            Mail::to($event->message->user)->send(new NotifyMessageTranslated($event->message));
        }
    }
}
