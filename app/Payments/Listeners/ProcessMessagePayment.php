<?php

namespace App\Payments\Listeners;

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\Exceptions\ChargeFailedException;
use App\Payments\Exceptions\MissingUnitPriceException;
use App\Payments\MessageReceipt;
use App\Translation\Contracts\Translator;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Events\ReplyReceived;
use App\Translation\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

/**
 * Class ProcessMessagePayment
 * @package App\Payments\Listeners
 */
class ProcessMessagePayment
{

    /**
     * Translator
     * @var Translator
     */
    protected $translator;
    /**
     * Message
     * @var Message
     */
    protected $message;
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
     * @param NewMessageRequestReceived|ReplyReceived $event
     * @throws ChargeFailedException
     * @throws \Exception
     */
    public function handle($event)
    {
        $this->translator = $event->translator;
        $this->message = $event->message;

        $this->setUnitPrice()
             ->setCredits()
             ->setChargeAmount()
             ->chargeUser()
             ->adjustUserCredits()
             ->recordUserCreditTransaction()
             ->createReceipt();
    }

    /**
     * Determine price per word for the Message.
     *
     * @return ProcessMessagePayment
     * @throws MissingUnitPriceException
     * @throws \Exception
     */
    protected function setUnitPrice()
    {
        try {
            $this->unitPrice = $this->translator->unitPrice($this->message->sourceLanguage, $this->message->targetLanguage);
            // TODO ::: Adjust unit price according to subscription plan
        } catch (\Exception $e) {
            $this->cancelTranslation();
            throw new MissingUnitPriceException();
            // TODO ::: Implement exception handler to redirect user and notify of failure
        }

        return $this;
    }

    /**
     * How many credits to use for Message.
     *
     * @return ProcessMessagePayment
     */
    protected function setCredits()
    {
        // Can't use more credits than we have
        $this->credits = min($this->message->word_count, $this->message->owner->credits);
        return $this;
    }

    /**
     * How much to charge User.
     *
     * @return ProcessMessagePayment
     */
    protected function setChargeAmount()
    {
        $wordCount = $this->message->word_count;
        // Can't charge less words than 0
        $chargeableWordCount = max($wordCount - $this->credits, 0);
        $this->chargeAmount = $chargeableWordCount * $this->unitPrice * 100;     // x 100 because Stripe accepts charge amount in cents
        return $this;
    }


    /**
     * Charge User.
     *
     * @return ProcessMessagePayment
     * @throws ChargeFailedException
     * @throws \Exception
     */
    protected function chargeUser()
    {
        try {
            $this->message->owner->charge($this->chargeAmount);
        } catch (\Exception $e) {
            // Failed charging user...
            $this->cancelTranslation();
            throw new ChargeFailedException(); // Don't go to next event listener
            // TODO ::: Implement handling ChargeFailedException to tell User that message won't be sent
            // because we couldn't charge him.
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
            $this->message->owner->credits($this->message->owner->credits() + $this->credits);
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
            CreditTransaction::record($this->message->owner, CreditTransactionType::payment(), $this->credits);
        }
        return $this;
    }

    /**
     * Creates and stores a Message Receipt.
     */
    protected function createReceipt()
    {
        MessageReceipt::makeFor($this->message, $this->unitPrice, $this->chargeAmount);
    }

    /**
     * Cancel Translation job.
     *
     * @throws \Exception
     */
    protected function cancelTranslation()
    {
        try {
            $this->translator->cancelTranslating($this->message);
        } catch (\Exception $e) {
            if (App::environment('local')) throw $e;
        }
    }
}