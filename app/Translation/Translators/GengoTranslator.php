<?php

namespace App\Translation\Translators;

use App\GengoError;
use App\Translation\Contracts\Translator;
use App\Language;
use App\Translation\Message;
use Gengo\Config;
use Gengo\Jobs as GengoJobs;
use Gengo\Service as GengoService;

class GengoTranslator implements Translator
{
    public function __construct()
    {
        $this->setup();
    }

    /**
     * Sets up our APP to use Gengo API
     */
    protected function setup()
    {
        Config::setAPIKey(env('GENGO_API'));
        Config::setPrivateKey(env('GENGO_SECRET'));
        Config::setResponseFormat("json");
        if(env('APP_ENV') == 'production') {
            Config::useProduction();
        }

        // (?) TODO ::: Check if we're already set-up so that we're
        //             not doing it every time we request a new
        //             translation.
    }

    /**
     * Language pairs available.
     *
     * @return mixed
     */
    public function getLanguagePairs()
    {
        return json_decode((new GengoService)->getLanguagePairs())->response;
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
            'slug' => "{$message->sender->name} :: {$message->subject} :: Message ID: {$message->hash}",
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
     * Adds translation job to Gengo's internal
     * queue.
     *
     * @param Message $message
     * @return void
     */
    public function translate(Message $message)
    {

        $jobs = [
            "jobs_01" => $this->buildJob($message)
        ];

        $jobsAPI = new GengoJobs;
        $jobsAPI->postJobs($jobs);

        $response = json_decode($jobsAPI->getResponseBody(), true);
        // Get response status as per Gengo API docs.
        $status = $response["opstat"];

        if ($status == "error") {

            // Mark and store error.
            $message->markError();
            GengoError::record($message, $response);

            // Things fail silently here because we assume that the error
            // was system fault and not User. So we'll go back
            // and attempt to rectify error in background.

        }
    }

    /**
     * Gets the cost per word for given language pair.
     *
     * @param Language $sourceLangue
     * @param Language $targetLanguage
     * @return mixed
     */
    public function unitPrice(Language $sourceLangue, Language $targetLanguage)
    {
        // TODO: Implement unitPrice() method.
    }
}
