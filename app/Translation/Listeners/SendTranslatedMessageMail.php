<?php

namespace App\Translation\Listeners;

use App\Translation\Events\MessageTranslated;
use App\Translation\Mail\TranslatedMessageForRecipient;
use App\Translation\Mail\TranslatedMessageForSendToSelf;
use App\Translation\Message;
use App\Translation\RecipientType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTranslatedMessageMail implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  MessageTranslated  $event
     * @return void
     */
    public function handle($event)
    {
        // Send notification emails
        if($event->message->send_to_self) {
            // Send translated message back to sender
            Mail::to($event->message->owner)->send(new TranslatedMessageForSendToSelf($event->message));
        } else {
            // Send to recipients
            Mail::to($this->buildMailAddresses($event->message, RecipientType::standard()))
                ->cc($this->buildMailAddresses($event->message, RecipientType::cc()))
                ->bcc($this->buildMailAddresses($event->message, RecipientType::bcc()))
                ->send(new TranslatedMessageForRecipient($event->message));
        }
    }

    /**
     * Building an array of email addresses for given type of Recipient.
     *
     * @param Message $message
     * @param RecipientType $type
     * @return array
     */
    protected function buildMailAddresses(Message $message, RecipientType $type)
    {
        $addresses = [];
        foreach ($message->recipients->where('recipient_type_id', $type->id) as $recipient) {
            array_push($addresses, ['email' => $recipient->email]);
        }
        return $addresses;
    }

}
