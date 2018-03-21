<?php

namespace App\Translation\Translators;

use App\Contracts\Translation\Translator;
use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
use App\Translation\Order;
use App\Translation\Order\OrderStatus;
use App\Translation\Translators\Gengo\GengoErrorResponse;
use App\Translation\Translators\Gengo\GengoLanguagePair;
use App\Translation\Translators\Gengo\GengoResponse;
use App\Translation\Translators\Gengo\GengoTranslationJob;
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
     * @throws \Gengo\Exception
     */
    public function unitCount(Language $sourceLanguage, Language $targetLanguage, $text)
    {
        $api = new GengoService();
        $job = GengoTranslationJob::forQuote($sourceLanguage, $targetLanguage, $text)->build();
        return (new GengoResponse($api->quote($job)))->body()["jobs"]["job_01"]["unit_count"];
    }

    /**
     * Check the cost per word for given language pair.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @return int
     * @throws \Gengo\Exception
     */
    public function unitPrice(Language $sourceLanguage, Language $targetLanguage)
    {
        // Get relevant pair
        $pair = $this->languagePair($sourceLanguage->code, $targetLanguage->code);

        // Manually reset object key pointer to the first index and removes old
        // key.
        $pair = reset($pair);

        // Multiply by 100 to work in cents.
        $unitPrice = $pair["unit_price"] * 100;

        return (int)$unitPrice;
    }

    /**
     * Start translating using Gengo.
     *
     * Adds translation job to Gengo's internal
     * queue.
     *
     * @param Message $message
     * @throws TranslationException
     * @throws \Gengo\Exception
     */
    public function translate(Message $message)
    {
        $api = new GengoJobs;
        // Create and post job according to Gengo's API
        $job = GengoTranslationJob::forMessage($message)->build();
        $response = new GengoResponse($api->postJobs($job));
        if ($response->wasSuccessful()) {
            // Get unit count and price to store with Order.
            $unitCount = $this->unitCount($message->sourceLanguage, $message->targetLanguage, $message->body);
            $unitPrice = $this->unitPrice($message->sourceLanguage, $message->targetLanguage);
            // Create order using Gengo's order id.
            $message->newOrder()
                    ->id($response->orderId())
                    ->unitCount($unitCount)
                    ->unitPrice($unitPrice)
                    ->save();
        } else {
            $error = new GengoErrorResponse($response->error());
            throw new TranslationException($error->description(), $error->code());
        };
    }

    /**
     * Cancels the translation Order.
     *
     * @param Order $order
     * @return bool|mixed
     * @throws CouldNotCancelTranslationException
     * @throws \Gengo\Exception
     */
    public function cancelTranslating(Order $order)
    {
        $api = new GengoOrder();
        try {
            // Gengo needs time to process brand new jobs
            sleep(5);
            // TODO(?) ::: Finding a better way to do this instead of guessing
            // the amount of time Gengo takes to process a job.
            $response = new GengoResponse($api->cancel($order->id));

            if ($response->wasSuccessful()) {
                $order->updateStatus(OrderStatus::cancelled());
                return true;
            } else {
                throw new \Exception();
            }
        } catch (\Exception $e) {
            throw new CouldNotCancelTranslationException();
        }
    }
}
