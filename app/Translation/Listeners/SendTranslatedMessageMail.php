<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\RecipientTranslatedMessage;
use App\Translation\Mail\SenderTranslatedMessage;
use App\Translation\RecipientType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTranslatedMessageMail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Send notification emails
        if($event->message->send_to_self) {
            // Send translated message back to sender
            Mail::to($event->message->user)->send(new SenderTranslatedMessage($event->message));
        } else {

            $recipients = $event->message->recipients;
            $standardRecipients = $recipients->where('recipient_type_id', RecipientType::standard()->id);
            $ccRecipients = $recipients->where('recipient_type_id', RecipientType::cc()->id);
            $bccRecipients = $recipients->where('recipient_type_id', RecipientType::bcc()->id);

            // Send to recipients
            Mail::to($standardRecipients)
                ->cc($ccRecipients)
                ->bcc($bccRecipients)
                ->send(new RecipientTranslatedMessage($event->message));

            // If Message is a reply, also send to original sender.
            if($event->message->is_reply)  {
                Mail::to($event->message->user)->send(new RecipientTranslatedMessage($event->message));
            }

            // TODO(?) ::: When the message is a reply, send a different mail to indicate
            // a reply.
        }
    }
}
