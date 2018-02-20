<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyErrorOccurred;
use App\Translation\Mail\ErrorSendingReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReplyNotSentNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  ReplyErrorOccurred  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->from)->send(new ErrorSendingReply($event->originalMessage, $event->subject, $event->body));
    }

}
