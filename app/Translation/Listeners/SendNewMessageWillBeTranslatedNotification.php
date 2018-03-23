<?php

namespace App\Translation\Listeners;

use App\Translation\Events\NewMessageCreated;
use App\Translation\Mail\NewMessageWillBeTranslatedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendNewMessageWillBeTranslatedNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  NewMessageCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->owner)->send(new NewMessageWillBeTranslatedNotification($event->message));
    }
}
