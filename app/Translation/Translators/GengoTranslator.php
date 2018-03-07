<?php

namespace App\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
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
        if(env('APP_ENV') == 'production' && !$this->test) GengoConfig::useProduction();
    }

    /**
     * Language pairs available.
     *
     * @param String $langSrc
     * @param String $langTgt
     * @return mixed
     * @throws \Gengo\Exception
     */
    public function getLanguagePairs($langSrc = null, $langTgt = null)
    {
        $service = new GengoService;
        $languagePairs = json_decode($service->getLanguagePairs($langSrc), true)["response"];
        return array_filter($languagePairs, function ($languagePair) use ($langTgt) {
            // Only want to view 'standard' tier level translations on Gengo
            $standardTier = $languagePair["tier"] == "standard";
            // No target language supplied - return all language pairs
            if(!$langTgt) return $standardTier;
            // Find pair with target language
            $targetPair = $languagePair["lc_tgt"] == $langTgt;
            return $targetPair && $standardTier;
        });
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
        $pair = $this->getLanguagePairs($sourceLangue->code, $targetLanguage->code);
        // Reset object key pointer to the first. Otherwise the relevant pair
        // might have a random key - ie. 5
        return reset($pair)->unit_price;
    }

    /**
     * Fill out Gengo's Job fields.
     *
     * @param Message $message
     * @return array
     */
    protected function buildJob(Message $message)
    {
        return [
            'type' => 'text',
            'slug' => "{$message->senderName()} :: {$message->subject} :: Message ID: {$message->hash}",
            'body_src' => $message->body,
            'lc_src' => $message->sourceLanguage->code,
            'lc_tgt' => $message->targetLanguage->code,
            'tier' => 'standard',
            'auto_approve' => 1,
            'force' => 0,
            'callback_url' => env('GENGO_CALLBACK_URL'),
            'custom_data' => "{
                \"message_id\": \"{$message->hash}\"
            }",
            'use_preferred' => 0
        ];
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
        $jobs = [
            "jobs_01" => $this->buildJob($message)
        ];
        $jobsAPI = new GengoJobs;
        $jobsAPI->postJobs($jobs);
        $response = json_decode($jobsAPI->getResponseBody(), true);
        if(! $this->operationWasSuccessful($response)) {
            // Some 'system' error (ie. our fault)
            $error = $this->parseErrorFromResponse($response);
            throw new TranslationException($error["description"], $error["code"]);
        }
    }

    /**
     * Retrives error information out of Gengo response.
     *
     * @param $response
     * @return array
     */
    public function parseErrorFromResponse($response)
    {
        // Error could be due to the job (ie. unsupported language pair) or
        // gengo system (not enough Gengo credits). Either case, this
        // is a system error on our part.
        $isJobError = array_key_exists("jobs_01", $response["err"]);

        // Depending on whether it's a job / gengo system error, the error code
        // and message is stored under different properties.
        $code = $isJobError ? $response["err"]["jobs_01"][0]["code"] : $response["err"]["code"];
        $msg = $isJobError ? $response["err"]["jobs_01"][0]["msg"] : $response["err"]["msg"];

        // Write our own custom description to clarify whether it was a Job / System error
        $description = $isJobError ? "Gengo Job: {$msg}" : "Gengo System: {$msg}";

        return [
            "code" => $code,
            "description" => $description
        ];
    }

    /**
     * Cancels the translation of a Message.
     *
     * @param Message $message
     * @throws CouldNotCancelTranslationException
     * @throws \Gengo\Exception
     */
    public function cancelTranslating(Message $message)
    {
        $order = new GengoOrder();
        $response = json_decode($order->cancel($message->gengo_order_id), true);
        if( ! $this->operationWasSuccessful($response)) throw new CouldNotCancelTranslationException();
    }

    /**
     * Gengo API successful
     *
     * @param array $response
     * @return bool
     */
    protected function operationWasSuccessful(array $response)
    {
        return $response["opstat"] == "ok";
    }

}
