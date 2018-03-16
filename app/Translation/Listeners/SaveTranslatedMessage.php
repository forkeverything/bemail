<?php

namespace App\Translation\Listeners;

use App\Translation\Events\MessageTranslated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveTranslatedMessage
{
    /**
     * Handle the event.
     *
     * @param  MessageTranslated  $event
     * @return void
     */
    public function handle(MessageTranslated $event)
    {
        $event->message->translatedBody($event->translatedText);
    }
}
