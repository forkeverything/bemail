<?php

namespace App\Translation\Listeners;

use App\Translation\Contracts\Translator;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslationException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslateNewMessage
{

    /**
     * @var Translator
     */
    private $translator;

    public function __construct()
    {
        $this->translator = resolve(Translator::class);
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws TranslationException
     */
    public function handle($event)
    {
        try {
            $this->translator->translate($event->message);
        } catch (TranslationException $e) {
            event(new TranslationErrorOccurred($event->message, $e));
            throw $e;
        }
    }
}
