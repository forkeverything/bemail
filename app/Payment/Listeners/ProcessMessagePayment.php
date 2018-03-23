<?php

namespace App\Payment\Listeners;

use Exception;
use Illuminate\Support\Facades\App;
use App\Payment\Events\FailedChargingUserForMessage;
use App\Payment\Credit\Transaction\CreditTransactionType;
use App\Payment\Exceptions\CouldNotChargeUserException;
use App\Contracts\Translation\Translator;
use App\Translation\Events\NewMessageCreated;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Message;
use App\Translation\Order\OrderStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ProcessMessagePayment
 * @package App\Payment\Listeners
 */
class ProcessMessagePayment implements ShouldQueue
{

    /**
     * Message
     * @var Message
     */
    protected $message;
    /**
     * How many units to charge for.
     *
     * @var
     */
    protected $unitCount;
    /**
     * Unit Price
     *
     * @var int
     */
    protected $unitPrice;
    /**
     * Credits
     *
     * @var int
     */
    protected $credits;
    /**
     * Charge Amount
     *
     * @var int
     */
    protected $chargeAmount;

    /**
     * Handle the event.
     *
     * @param NewMessageCreated|ReplyMessageCreated $event
     * @throws CouldNotChargeUserException
     */
    public function handle($event)
    {
        $this->message = $event->message;

        $this->setCredits()
             ->setUnitCount()
             ->setUnitPrice()
             ->setChargeAmount()
             ->chargeUser()
             ->adjustUserCredits()
             ->recordUserCreditTransaction()
             ->createReceipt();

    }


    /**
     * Set amount of User word credits to use.
     *
     * @return ProcessMessagePayment
     */
    protected function setCredits()
    {
        // Can't use more credits than the User has.
        $this->credits = min($this->message->order->unit_count, $this->message->owner->credits);
        return $this;
    }

    /**
     * Set how many units to charge User for.
     *
     * @return $this
     */
    protected function setUnitCount()
    {
        // Chargeable units = total message units - user credits
        // Can't charge less than 0 units.
        $this->unitCount = max($this->message->order->unit_count - $this->credits, 0);
        return $this;
    }

    /**
     * Set unit price from Message's Order.
     *
     * @return ProcessMessagePayment
     */
    protected function setUnitPrice()
    {
        $this->unitPrice = $this->message->order->unit_price;
        return $this;
    }

    /**
     * Set how much to charge User.
     *
     * @return ProcessMessagePayment
     */
    protected function setChargeAmount()
    {
        $translator = $this->unitCount * $this->unitPrice;
        $bemail = $this->unitCount * $this->message->owner->plan()->surcharge();
        $this->chargeAmount = $translator + $bemail;
        return $this;
    }


    /**
     * Actually charge the User.
     *
     * @return ProcessMessagePayment
     * @throws CouldNotChargeUserException
     */
    protected function chargeUser()
    {

        // Stripe will error on attempt to charge 0.
        if (!$this->chargeAmount) {
            return $this;
        }

        try {
            $this->message->owner->charge($this->chargeAmount);
        } catch (\Exception $e) {
            event(new FailedChargingUserForMessage($this->message));
            throw new CouldNotChargeUserException($e->getMessage(), $e->getCode());
        }

        return $this;
    }

    /**
     * Any part of payment using credits?
     *
     * @return bool
     */
    protected function isUsingCredits()
    {
        return $this->credits > 0;
    }

    /**
     * Adjust User word credits amount.
     *
     * @return $this
     */
    protected function adjustUserCredits()
    {
        if ($this->isUsingCredits()) {
            $this->message->owner->credits($this->message->owner->credits() - $this->credits);
        }
        return $this;
    }

    /**
     * Record the credit payment.
     *
     * @return $this
     */
    protected function recordUserCreditTransaction()
    {
        if ($this->isUsingCredits()) {
            $this->message->owner->recordNewCreditTransaction()
                                 ->type(CreditTransactionType::payment())
                                 ->amount($this->credits)
                                 ->save();
        }
        return $this;
    }

    /**
     * Creates and stores a Message Receipt.
     */
    protected function createReceipt()
    {
        $this->message->newReceipt()
                      ->amountCharged($this->chargeAmount)
                      ->save();

    }
}
