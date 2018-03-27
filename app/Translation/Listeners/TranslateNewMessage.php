<?php

namespace App\Translation\Listeners;

use App\Contracts\Translation\Translator;
use App\Traits\LogsExceptions;
use App\Translation\Events\NewMessageCreated;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslatorException\FailedTranslatingMessageException;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TranslateNewMessage implements ShouldQueue
{

    use LogsExceptions;

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
    public function handle(NewMessageCreated $event)
    {
        $this->translator->translate($event->message);
    }

    /**
     * Handle job failure.
     *
     * @param NewMessageCreated $event
     * @param Exception $exception
     */
    public function failed(NewMessageCreated $event, $exception)
    {
        event(new TranslationErrorOccurred($event->message, $exception));
        $this->logException('FAILED_TRANSLATING_NEW_MESSAGE', $exception);
    }
}
