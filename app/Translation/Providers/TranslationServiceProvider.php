<?php

namespace App\Translation\Providers;

use App\Contracts\InboundMail\ReplyHandler;
use App\Contracts\Translation\Translator;
use App\InboundMail\Postmark\PostmarkReplyHandler;
use App\Translation\Translators\GengoTranslator;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Translating using Gengo
        $this->app->singleton(Translator::class, GengoTranslator::class);

        // Inbound mail using Postmark
        $this->app->bind(
            ReplyHandler::class,
            PostmarkReplyHandler::class
        );
    }
}
