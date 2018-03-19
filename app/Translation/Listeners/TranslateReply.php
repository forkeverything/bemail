<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyErrorOccurred;
use App\Translation\Events\ReplyReceived;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslationException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class TranslateReply
{

    /**
     * Handle the event.
     *
     * @param  ReplyReceived $event
     * @return mixed
     * @throws TranslationException
     */
    public function handle($event)
    {
        try {
            $event->translator->translate($event->message);
        } catch (TranslationException $e) {
            event(new TranslationErrorOccurred($event->message, $e));
            event(new ReplyErrorOccurred($event->message));
            if (App::environment('local')) throw $e;
            return false;
        }
    }
}
