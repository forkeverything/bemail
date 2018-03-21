<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyReceived;
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
                                                 ->senderEmail($event->inboundMailRequest->fromAddress())
                                                 ->senderName($event->inboundMailRequest->fromName())
                                                 ->subject($event->inboundMailRequest->subject())
                                                 ->body($event->inboundMailRequest->strippedReplyBody())
                                                 ->make();
    }
}
