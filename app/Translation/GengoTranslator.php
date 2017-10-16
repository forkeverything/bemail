<?php

namespace App\Translation;

use App\Contracts\Translation\Translator;
use App\Message;
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

    public function translate(Message $message)
    {
        $job = [
            'type' => 'text',
            'slug' => "{$message->user->name} :: {$message->subject} :: Message ID: {$message->id}",
            'body_src' => $message->body,
            'lc_src' => $message->sourceLanguage->code,
            'lc_tgt' => $message->targetLanguage->code,
            'tier' => 'standard',
            'auto_approve' => 1,
            'force' => 0,
            'callback_url' => env('GENGO_CALLBACK_URL'),
            'custom_data' => "{
                \"message_id\": \"{$message->id}\"
            }",
            'use_preferred' => 0
        ];

        $jobs = [
            "jobs_01" => $job
        ];

        $jobsAPI = new GengoJobs;

        $jobsAPI->postJobs($jobs);

        $response = json_decode($jobsAPI->getResponseBody(), true);
    }

}
