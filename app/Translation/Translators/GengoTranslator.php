<?php

namespace App\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
use App\Translation\Order;
use App\Translation\OrderStatus;
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
     * Testing?
     *
     * Set to True from GengoTranslatorTest. This ensures
     * that tests are sent to the sandbox, regardless
     * of app environment.
     *
     * @var bool
     */
    public $test = false;

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
        if (env('APP_ENV') == 'production' && !$this->test) GengoConfig::useProduction();
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
            ->filterTargetLanguage($langTgt)
            ->result();
    }

    /**
     * Check the cost per word for given language pair.
     *
     * @param Language $sourceLangue
     * @param Language $targetLanguage
     * @return float
     * @throws \Gengo\Exception
     */
    public function unitPrice(Language $sourceLangue, Language $targetLanguage)
    {
        // Get relevant pair
        $pair = $this->languagePair($sourceLangue->code, $targetLanguage->code);

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
        // Create and post job according to Gengo's API
        $job = (new GengoTranslationJob($message))->build();
        $api = new GengoJobs;
        $response = new GengoResponse($api->postJobs($job));
        if ($response->wasSuccessful()) {
            // Create order using Gengo's order id.
            $message->createOrder($response->orderId());
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
