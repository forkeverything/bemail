<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Mail\blade;
use App\Translation\Mail\MessageNotTranslatedDueToSystemErrorNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMessageNotTranslatedDueToSystemErrorNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  TranslationErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->message->sender_email)->send(new MessageNotTranslatedDueToSystemErrorNotification($event->message));
    }
}
