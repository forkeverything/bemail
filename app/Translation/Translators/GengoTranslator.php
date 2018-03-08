<?php

namespace App\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
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
     * @return mixed
     * @throws \Gengo\Exception
     */
    public function unitPrice(Language $sourceLangue, Language $targetLanguage)
    {
        // Get relevant pair
        $pair = $this->languagePair($sourceLangue->code, $targetLanguage->code);
        // Manually reset object key pointer to the first index. Otherwise
        // the relevant pair might still have a previous key - ie. 5
        return reset($pair)->unit_price;
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
            // Store Gengo order id on message
            $orderId = $response->body()["order_id"];
            $message->gengoOrderId($orderId);
        } else {
            $error = new GengoErrorResponse($response->error());
            throw new TranslationException($error->description(), $error->code());
        };
    }

    /**
     * Cancels the translation of a Message.
     *
     * @param Message $message
     * @return bool|mixed
     * @throws CouldNotCancelTranslationException
     * @throws \Gengo\Exception
     */
    public function cancelTranslating(Message $message)
    {
        $api = new GengoOrder();
        $response = new GengoResponse($api->cancel($message->gengoOrderId()));
        if ($response->wasSuccessful()) {
            return true;
        } else {
            throw new CouldNotCancelTranslationException();
        }
    }
}
