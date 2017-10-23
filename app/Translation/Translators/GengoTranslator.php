<?php

namespace App\Translation\Translators;

use App\Translation\Contracts\Translator;
use App\Language;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
use App\Translation\MessageError;
use App\Translation\TranslationStatus;
use Gengo\Config;
use Gengo\Jobs as GengoJobs;
use Gengo\Service as GengoService;

class GengoTranslator implements Translator
{
    /**
     * GengoTranslator constructor.
     */
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
     *–
     * @param Message $message
     * @return void
     * @throws TranslationException
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
            $message->updateStatus(TranslationStatus::error());
            $messageError = $this->recordError($message, $response);

            // Throw error;
            throw new TranslationException($messageError->description);

        }
    }

    /**
     * Parse error from response and record it for given Message.
     *
     * @param Message $message
     * @param $response
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function recordError(Message $message, $response)
    {
        // Error could be due to the job (ie. unsupported language pair) or system (not enough Gengo credits).
        $isJobError = array_key_exists("jobs_01", $response["err"]);

        $code = $isJobError ? $response["err"]["jobs_01"][0]["code"] : $response["err"]["code"];
        $msg = $isJobError ? $response["err"]["jobs_01"][0]["msg"] : $response["err"]["msg"];
        $description = $isJobError ? "Gengo Job: {$msg}" : "Gengo System: {$msg}";

        return MessageError::create([
            'code' => $code,
            'description' => $description,
            'message_id' => $message->id
        ]);
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
