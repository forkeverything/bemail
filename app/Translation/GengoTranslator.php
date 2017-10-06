<?php

namespace App\Translation;

use App\Contracts\Translation\Translator;
use Gengo\Config;

class GengoTranslator implements Translator
{
    public function __construct()
    {
        Config::setAPIKey(env('GENGO_API'));
        Config::setPrivateKey(env('GENGO_SECRET'));
        Config::setResponseFormat("json");
        if(env('APP_ENV') == 'production') {
            Config::useProduction();
        }
    }

    public function getLanguagePairs()
    {
        return json_decode((new \Gengo\Service())->getLanguagePairs())->response;
    }

    public function translate()
    {
        // TODO: Implement translate() method.
    }

}
