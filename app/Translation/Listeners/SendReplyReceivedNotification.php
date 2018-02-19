<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyReceived;
use App\Translation\Mail\MessageReplyReceivedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReplyReceivedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ReplyReceived  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->senderEmail())->send(new MessageReplyReceivedNotification($event->message));
    }
}
