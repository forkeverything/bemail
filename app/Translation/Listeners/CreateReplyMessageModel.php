<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyReceived;
use App\Translation\Message;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateReplyMessageModel
{

    /**
     * Handle the event.
     *
     * @param  ReplyReceived $event
     * @return void
     * @throws \Exception
     */
    public function handle($event)
    {
        $event->message = $event->originalMessage->newReply()
                                                 ->senderEmail($event->postmarkRequest->fromAddress())
                                                 ->senderName($event->postmarkRequest->fromName())
                                                 ->subject($event->postmarkRequest->subject())
                                                 ->body($event->postmarkRequest->strippedTextBody())
                                                 ->make();
    }
}
