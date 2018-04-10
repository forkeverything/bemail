<?php

namespace App\Payment\Listeners;

use App\Payment\Events\FailedChargingUserForMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordChargeErrorOnUser
{
    /**
     * Handle the event.
     *
     * @param  FailedChargingUserForMessage  $event
     * @return void
     */
    public function handle(FailedChargingUserForMessage $event)
    {
        $user = $event->message->owner;
        $error = $user->newError();

        $charge = $this->formattedChargeAmount($event->chargeAmount);
        $card = $user->card_last_four;
        $subject = $event->message->subject ? " ({$event->message->subject})": '';

        $error->code = '801';
        // TODO ::: Come up with custom error coding system
        $error->msg = "Failed charging {$charge} to card ending in {$card} for message {$event->message->hash}{$subject}.";

        $error->save();
    }

    /**
     * Convert cents into dollars and add $.
     *
     * @param $chargeAmount
     * @return string
     */
    private function formattedChargeAmount($chargeAmount)
    {
        $dollars =  number_format(($chargeAmount /100), 2, '.');
        return "${$dollars}";
    }
}
