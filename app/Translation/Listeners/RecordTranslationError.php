<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordTranslationError
{

    /**
     * Handle the event.
     *
     * @param  TranslationErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->newError()
        ->code($event->exception->getCode())
        ->msg($event->exception->getMessage())
        ->save();
    }
}
