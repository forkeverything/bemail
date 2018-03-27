<?php

namespace App\Translation\Listeners;

use App\Contracts\Translation\Translator;
use App\Traits\LogsExceptions;
use App\Translation\Events\ReplyMessageCreated;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslatorException\FailedTranslatingMessageException;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class TranslateReplyMessage implements ShouldQueue
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
     * @param  ReplyMessageCreated $event
     * @throws Exception
     */
    public function handle(ReplyMessageCreated $event)
    {
        $this->translator->translate($event->message);
    }

    /**
     * Handle job failure.
     *
     * @param ReplyMessageCreated $event
     * @param Exception $exception
     */
    public function failed(ReplyMessageCreated $event, Exception $exception)
    {
        event(new TranslationErrorOccurred($event->message, $exception));
        $this->logException('FAILED_TRANSLATING_REPLY_MESSAGE', $exception);
    }

}
