<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyReceived;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Contracts\InboundMail\InboundMailRequest;
use App\Contracts\InboundMail\InboundMailRecipient;
use App\Translation\Recipient\RecipientType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRecipientsForReplyMessage
{
    /**
     * Handle the event.
     *
     * @param  ReplyReceived  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message->newRecipients()
                ->recipientEmails($this->recipientEmails($event->inboundMailRequest, $event->originalMessage))
                ->make();
    }

    /**
     * Creates RecipientEmails.
     *
     * @param Request $postmarkRequest
     * @param Message $originalMessage
     * @return RecipientEmails
     */
    protected function recipientEmails(InboundMailRequest $postmarkRequest, Message $originalMessage)
    {

        $recipientEmails = RecipientEmails::new();

        $types = [
            'standard',
            'cc',
            'bcc'
        ];


        foreach ($types as $type) {
            $recipients = call_user_func([$postmarkRequest, "{$type}Recipients"]);
            foreach ($recipients as $inboundMailRecipient) {
                /**
                 * @var $inboundMailRecipient InboundMailRecipient
                 */
                $recipientEmails->addEmailToType($inboundMailRecipient->email(), call_user_func("RecipientType::{$type}"));
            }
        }

        // Manually add original Message sender email as recipient because
        // the reply address is bemail's inbound address.
        $recipientEmails->addEmailToType($originalMessage->sender_email, RecipientType::standard());

        return $recipientEmails;
    }
}
