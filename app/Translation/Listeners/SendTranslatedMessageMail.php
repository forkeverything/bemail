<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\TranslatedMessageForRecipient;
use App\Translation\Mail\TranslatedMessageForSendToSelf;
use App\Translation\Message;
use App\Translation\RecipientType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTranslatedMessageMail
{

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
            Mail::to($event->message->user)->send(new TranslatedMessageForSendToSelf($event->message));
        } else {
            // Send to recipients
            Mail::to($this->buildMailAddresses($event->message, RecipientType::standard()))
                ->cc($this->buildMailAddresses($event->message, RecipientType::cc()))
                ->bcc($this->buildMailAddresses($event->message, RecipientType::bcc()))
                ->send(new TranslatedMessageForRecipient($event->message));
            // TODO(?) ::: When the message is a reply, send a different mail to indicate
            // a reply.
        }
    }

    protected function buildMailAddresses(Message $message, RecipientType $type)
    {
        $addresses = [];
        foreach ($message->recipients->where('recipient_type_id', $type->id) as $recipient) {
            array_push($addresses, ['email' => $recipient->email]);
        }
        return $addresses;
    }

}
