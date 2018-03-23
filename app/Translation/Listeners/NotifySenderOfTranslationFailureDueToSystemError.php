<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Mail\MessageWillNotTranslateNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifySenderOfTranslationFailureDueToSystemError implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  TranslationErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->sender_email)->send(new MessageWillNotTranslateNotification($event->message));
    }
}
