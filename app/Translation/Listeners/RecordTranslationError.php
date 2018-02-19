<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\MessageError;
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
        MessageError::create([
            'code' => $event->exception->getCode(),
            'description' => $event->exception->getMessage(),
            'message_id' => $event->message->id
        ]);
    }
}
