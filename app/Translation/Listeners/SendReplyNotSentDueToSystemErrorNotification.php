<?php

namespace App\Translation\Listeners;

use App\Translation\Events\FailedCreatingReply;
use App\Translation\Mail\ReplyNotSentDueToSystemErrorNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReplyNotSentDueToSystemErrorNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  FailedCreatingReply  $event
     * @return void
     */
    public function handle(FailedCreatingReply $event)
    {
        Mail::to($event->senderEmail)->send(new ReplyNotSentDueToSystemErrorNotification(
            $event->originalMessage,
            $event->standardRecipients,
            $event->ccRecipients,
            $event->bccRecipients,
            $event->replySubject,
            $event->replyBody
        ));
    }
}
