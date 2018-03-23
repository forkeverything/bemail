<?php

namespace App\Translation\Translators;

use App\Contracts\Translation\Translator;
use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslatorException\FailedCreatingOrderException;
use App\Translation\Exceptions\TranslatorException\MessageCouldNotBeTranslatedException;
use App\Translation\Exceptions\TranslatorException\FailedGettingUnitCountException;
use App\Translation\Exceptions\TranslatorException\FailedGettingUnitPriceException;
use App\Translation\Message;
use App\Translation\Order;
use App\Translation\Order\OrderStatus;
use App\Translation\Translators\Gengo\GengoErrorResponse;
use App\Translation\Translators\Gengo\GengoLanguagePair;
use App\Translation\Translators\Gengo\GengoResponse;
use App\Translation\Translators\Gengo\GengoTranslationJob;
use Exception;
use Gengo\Config as GengoConfig;
use Gengo\Jobs as GengoJobs;
use Gengo\Service as GengoService;
use Gengo\Order as GengoOrder;

/**
 * GengoTranslator
 *
 * @package App\Translation\Translators
 */
class GengoTranslator implements Translator
{

    /**
     * GengoTranslator constructor.
     *
     * @throws \Gengo\Exception
     */
    public function __construct()
    {
        // Setup APP to use Gengo API
        GengoConfig::setAPIKey(env('GENGO_API'));
        GengoConfig::setPrivateKey(env('GENGO_SECRET'));
        GengoConfig::setResponseFormat("json");
        // Production or Sandbox
        if (env('APP_ENV') == 'production') GengoConfig::useProduction();
    }

    /**
     * Language pairs available.
     *
     * @param String $langSrc
     * @param String $langTgt
     * @return mixed
     * @throws \Gengo\Exception
     */
    public function languagePair($langSrc = null, $langTgt = null)
    {
        $api = new GengoService;
        $languagePairs = (new GengoResponse($api->getLanguagePairs($langSrc)))->body();

        return (new GengoLanguagePair($languagePairs))
            // Only want to view 'standard' tier level translations on Gengo
            ->filterTier("standard")
            ->filterSourceLanguage($langSrc)
            ->filterTargetLanguage($langTgt)
            ->result();
    }

    /**
     * The amount of units (words) the translator will charge for.
     *
     * This is different for various languages because for
     * certain languages, multiple characters make up
     * one single word.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @param $text
     * @return int
     * @throws FailedGettingUnitCountException
     * @throws Exception
     * @throws \Gengo\Exception
     */
    public function unitCount(Language $sourceLanguage, Language $targetLanguage, $text)
    {
        try {
            $api = new GengoService();
            $job = GengoTranslationJob::forQuote($sourceLanguage, $targetLanguage, $text)->build();
            return (new GengoResponse($api->quote($job)))->body()["jobs"]["job_01"]["unit_count"];
        } catch (\Exception $e) {
            throw new FailedGettingUnitCountException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check the cost per word for given language pair.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @return int
     * @throws \Gengo\Exception
     * @throws FailedGettingUnitPriceException
     */
    public function unitPrice(Language $sourceLanguage, Language $targetLanguage)
    {
        try {
            // Get relevant pair
            $pair = $this->languagePair($sourceLanguage->code, $targetLanguage->code);
            // Manually reset object key pointer to the first index and removes old
            // key.
            $pair = reset($pair);
            // Multiply by 100 to work in cents.
            $unitPrice = $pair["unit_price"] * 100;
            return (int)$unitPrice;
        } catch (\Exception $e) {
            throw new FailedGettingUnitPriceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Start translating using Gengo.
     *
     * Adds translation job to Gengo's internal
     * queue.
     *
     * @param Message $message
     * @throws Exception
     * @throws MessageCouldNotBeTranslatedException
     * @throws FailedCreatingOrderException
     * @throws \Gengo\Exception
     */
    public function translate(Message $message)
    {
        $api = new GengoJobs;
        // Create and post job according to Gengo's API
        $job = GengoTranslationJob::forMessage($message)->build();
        $response = new GengoResponse($api->postJobs($job));

        if (!$response->wasSuccessful()) {
            $error = new GengoErrorResponse($response->error());
            throw new MessageCouldNotBeTranslatedException($error->msg(), $error->code());
        }

        $this->createTranslationOrder($message, $response);
    }

    /**
     * Create Order after successfully posting translation job.
     *
     * @param Message $message
     * @param GengoResponse $response
     * @throws Exception
     * @throws FailedCreatingOrderException
     * @throws \Gengo\Exception
     */
    private function createTranslationOrder(Message $message, GengoResponse $response)
    {
        // Create Order in a try-catch because if it fails, need
        // to cancel translation job.
        try {
            // Get unit count and price to store with Order.
            $unitCount = $this->unitCount($message->sourceLanguage, $message->targetLanguage, $message->body);
            $unitPrice = $this->unitPrice($message->sourceLanguage, $message->targetLanguage);
            // Create order using Gengo's order id.
            $message->newOrder()
                    ->id($response->orderId())
                    ->unitCount($unitCount)
                    ->unitPrice($unitPrice)
                    ->save();
        } catch (Exception $e) {

            try {
                $this->cancelTranslating($response->orderId());
            } catch (Exception $cancelTranslatingException) {
                // Don't care why cancelling didn't work.
            }

            throw new FailedCreatingOrderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Cancels the translation Order.
     *
     * @param Order|int $order
     * @return bool|mixed
     * @throws CouldNotCancelTranslationException
     * @throws \Gengo\Exception
     */
    public function cancelTranslating($order)
    {

        // Cancelling Order model or using Gengo order Id directly?
        if (is_int($order)) {
            $orderId = $order;
        } else {
            $orderId = $order->id;
        }

        try {
            $api = new GengoOrder();

            // Gengo needs time to process brand new jobs
            sleep(5);
            // TODO(?) ::: Finding a better way to do this instead of guessing
            // the amount of time Gengo takes to process a job.

            $response = new GengoResponse($api->cancel($orderId));

            // Manually throw exception when request successfully sent
            // but encountered error response from Gengo.
            if (!$response->wasSuccessful()) {
                $error = new GengoErrorResponse($response->error());
                throw new CouldNotCancelTranslationException($error->msg(), $error->code());
            }

            if ($order instanceof Order) {
                /**
                 * @var Order $order
                 */
                $order->updateStatus(OrderStatus::cancelled());
            }

            return true;
        } catch (Exception $e) {
            throw new CouldNotCancelTranslationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
