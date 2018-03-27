<?php

namespace App\Translation\Listeners;

use App\Contracts\Translation\Translator;
use App\Payment\Events\FailedChargingUserForMessage;
use App\Traits\LogsExceptions;
use App\Translation\Exceptions\FailedCancellingTranslationException;
use App\Translation\Order\OrderStatus;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelTranslationOrder implements ShouldQueue
{

    use LogsExceptions;

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
     * @param  FailedChargingUserForMessage $event
     * @return void
     * @throws FailedCancellingTranslationException
     */
    public function handle(FailedChargingUserForMessage $event)
    {
        $this->translator->cancelTranslating($event->message->order);
    }

    /**
     * Handle job failure.
     *
     * @param FailedChargingUserForMessage $event
     * @param $exception
     */
    public function failed(FailedChargingUserForMessage $event, $exception)
    {

        // Mark Order as cancelled even if the actually cancelation failed.
        // This makes sure the Message won't be updated or sent when
        // the translation is complete.
        $event->message->order->updateStatus(OrderStatus::cancelled());

        $this->logException('FAILED_CANCELLING_TRANSLATION_ORDER', $exception);
    }
}
