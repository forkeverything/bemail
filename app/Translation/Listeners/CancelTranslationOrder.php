<?php

namespace App\Translation\Listeners;

use App\Contracts\Translation\Translator;
use App\Payment\Events\FailedChargingUserForMessage;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Order\OrderStatus;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelTranslationOrder
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->translator = resolve(Translator::class);
    }

    /**
     * Handle the event.
     *
     * @param  FailedChargingUserForMessage  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            // Nested try-catch to log why translation failed to cancel.
            try {
                $this->translator->cancelTranslating($event->message->order);
            } catch (CouldNotCancelTranslationException $e) {
                $e->report();
                throw $e;
            }
        } catch (Exception $e) {
            // Mark Order as cancelled even if the actually cancelation failed.
            // This makes sure the Message won't be updated or sent when
            // the translation is complete.
            $event->message->order->updateStatus(OrderStatus::cancelled());
        }
    }
}
