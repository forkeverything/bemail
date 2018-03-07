<?php

namespace App\Payment\Listeners;

use App\Payment\CreditTransactionType;
use App\Payment\Exceptions\ChargeFailedException;
use App\Payment\Exceptions\MissingUnitPriceException;
use App\Translation\Contracts\Translator;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Events\ReplyReceived;
use App\Translation\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

/**
 * Class ProcessMessagePayment
 * @package App\Payment\Listeners
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
        $this->credits = min($this->message->word_count, $this->message->owner->word_credits);
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
     * Adjust User word credits amount.
     *
     * @return $this
     */
    protected function adjustUserCredits()
    {
        $this->message->owner->adjustCredits(CreditTransactionType::payment(), $this->credits);
        return $this;
    }

    /**
     * Creates and stores a Message Receipt.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createReceipt()
    {
        return $this->message->receipt()->create([
            'cost_per_word' => $this->unitPrice,
            'amount_charged' => $this->chargeAmount
        ]);
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
