<?php

namespace App\Translation\Listeners;

use App\Translation\Events\MessageTranslated;
use App\Translation\TranslationStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTranslatedMessageAttributes
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(MessageTranslated $event)
    {
        // Store translated Body
        $event->message->translatedBody($event->translatedText);

        // Update message status
        $event->message->updateStatus(TranslationStatus::approved());
    }
}
