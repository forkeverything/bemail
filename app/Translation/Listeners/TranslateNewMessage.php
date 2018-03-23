<?php

namespace App\Translation\Listeners;

use App\Contracts\Translation\Translator;
use App\Translation\Events\NewMessageCreated;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslatorException\MessageCouldNotBeTranslatedException;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslateNewMessage implements ShouldQueue
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->translator = resolve(Translator::class);
    }

    /**
     * Handle the event.
     *
     * @param  NewMessageCreated $event
     * @return void
     * @throws Exception
     */
    public function handle($event)
    {
        try {
            $this->translator->translate($event->message);
        } catch (Exception $e) {
            event(new TranslationErrorOccurred($event->message, $e));
            throw $e;
        }
    }
}
