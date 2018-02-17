<?php

namespace App\Translation\Listeners;

use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Mail\ReceivedNewMessageRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNewMessageRequestReceivedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->owner)->send(new ReceivedNewMessageRequest($event->message));
    }
}
