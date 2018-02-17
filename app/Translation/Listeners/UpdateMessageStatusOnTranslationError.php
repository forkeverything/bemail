<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\TranslationStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateMessageStatusOnTranslationError
{

    /**
     * Handle the event.
     *
     * @param  TranslationErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->updateStatus(TranslationStatus::error());
    }
}
