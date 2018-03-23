<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Mail\ReplyMessageWillBeTranslatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReplyMessageWillBeTranslatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  ReplyMessageCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->sender_email)->send(new ReplyMessageWillBeTranslatedNotification($event->message));
    }
}
