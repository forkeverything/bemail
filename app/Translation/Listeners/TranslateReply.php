<?php

namespace App\Translation\Listeners;

use App\Translation\Contracts\Translator;
use App\Translation\Events\ReplyErrorOccurred;
use App\Translation\Events\ReplyReceived;
use App\Translation\Events\TranslationErrorOccurred;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Reply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class TranslateReply
{

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
     * @param  ReplyReceived $event
     * @return mixed
     * @throws TranslationException
     */
    public function handle($event)
    {
        try {
            $this->translator->translate($event->message);
        } catch (TranslationException $e) {
            event(new TranslationErrorOccurred($event->message, $e));
            event(new ReplyErrorOccurred($event->fromAddress, $event->originalMessage, $event->subject, $event->body));
            if (App::environment('local')) throw $e;
            return false;
        }
    }
}
