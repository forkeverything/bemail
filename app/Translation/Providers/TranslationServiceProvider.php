<?php

namespace App\Translation\Providers;

use App\Translation\Contracts\Translator;
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
        $this->app->singleton(Translator::class, GengoTranslator::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
