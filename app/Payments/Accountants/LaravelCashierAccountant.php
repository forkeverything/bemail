<?php


namespace App\Payments\Accountants;


use App\Language;
use App\Message;
use App\Payments\Contracts\Accountant;
use App\Payments\CreditTransactionType;
use App\User;

class LaravelCashierAccountant implements Accountant
{

    /**
     * User that's we're charging.
     *
     * @var User
     */
    private  $user;

    /**
     * Accountant constructor.
     * Need to know the User who we will be calculating
     * payment for.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Change the amount of word credits a User has.
     * Should only be done here so changes are recorded.
     *
     * @param CreditTransactionType $creditTransactionType
     * @param $amount
     * @return mixed
     */
    function adjustCredits(CreditTransactionType $creditTransactionType, $amount)
    {
        // TODO: Implement adjustCredits() method.
    }


    /**
     * Process the payment for given Message.
     *
     * @param Message $message
     * @return mixed
     */
    public function process(Message $message)
    {
        // Calculate charge amount
        $costPerWord = $this->checkCostPerWord($message->sourceLanguage, $message->targetLanguage);
        $wordCount = $message->word_count;
        $creditsToUse = min($wordCount, $this->user->word_credits);
        $chargeAmount = $this->calculateChargeAmount($wordCount, $creditsToUse, $costPerWord);

        // Charge the actual amount to User
        $this->charge($chargeAmount);

        // Successfully charged
            // Deduct credits
            $this->adjustCredits(CreditTransactionType::payment(), $creditsToUse);
            // Make receipt
            return $this->createMessageReceipt($message, $costPerWord, $chargeAmount);

    }

    /**
     * Get unit cost per word for given language pair.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     */
    protected function checkCostPerWord(Language $sourceLanguage, Language $targetLanguage)
    {
        $translator = resolve('App\Contracts\Translation\Translator');

        // Get the unit price that the translator charges.
        $translatorUnitPrice = $translator->unitPrice($sourceLanguage, $targetLanguage);

        // Adjust according to subscription plan.

        // TODO: Implement checkCostPerWord() method.
    }

    /**
     * Determine total amount to charge User.
     *
     * @param $wordCount
     * @param $creditsToUse
     * @param $costPerWord
     * @return mixed
     */
    private function calculateChargeAmount($wordCount, $creditsToUse, $costPerWord)
    {
        $chargeableWordCount = max( $wordCount - $creditsToUse, 0 );
        return $chargeableWordCount * $costPerWord;
    }

    /**
     * Create MessageReceipt
     *
     * @param Message $message
     * @param $wordCount
     * @param $costPerWord
     * @param $amountCharged
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function createMessageReceipt(Message $message, $costPerWord, $amountCharged)
    {
        return $message->receipt()->create([
            'cost_per_word' => $costPerWord,
            'amount_charged' => $amountCharged
        ]);
    }

    /**
     * Charge the User given amount.
     *
     * @param $amount
     * @return mixed
     */
    function charge($amount)
    {
        // TODO: Implement charge() method.
    }
}