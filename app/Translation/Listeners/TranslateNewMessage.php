<?php

namespace App\Translation\Listeners;

use App\Translation\Contracts\Translator;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslationException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslateNewMessage
{
    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived $event
     * @return void
     * @throws TranslationException
     */
    public function handle($event)
    {
        try {
            $event->translator->translate($event->message);
        } catch (TranslationException $e) {
            event(new TranslationErrorOccurred($event->message, $e));
            throw $e;
        }
    }
}
