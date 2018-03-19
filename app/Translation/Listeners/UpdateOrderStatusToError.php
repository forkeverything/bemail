<?php

namespace App\Translation\Listeners;

use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\OrderStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrderStatusToError
{

    /**
     * Handle the event.
     *
     * @param  TranslationErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->order->updateStatus(OrderStatus::error());
    }
}
