<?php

namespace App\Translation\Listeners;

use App\Translation\Order\OrderStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrderStatusToComplete
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->order->updateStatus(OrderStatus::complete());
    }
}
