<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyReceived;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\PostmarkInboundMailRequest;
use App\Translation\PostmarkInboundRecipient;
use App\Translation\RecipientType;
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
                ->recipientEmails($this->recipientEmails($event->postmarkRequest, $event->originalMessage))
                ->make();
    }

    /**
     * Creates RecipientEmails.
     *
     * @param PostmarkInboundMailRequest $postmarkRequest
     * @param Message $originalMessage
     * @return RecipientEmails
     */
    protected function recipientEmails(PostmarkInboundMailRequest $postmarkRequest, Message $originalMessage)
    {

        $recipientEmails = RecipientEmails::new();

        $types = [
            'standard',
            'cc',
            'bcc'
        ];


        foreach ($types as $type) {
            $recipients = call_user_func([$postmarkRequest, "{$type}Recipients"]);
            foreach ($recipients as $postmarkRecipient) {
                /**
                 * @var $postmarkRecipient PostmarkInboundRecipient
                 */
                $recipientEmails->addEmailToType($postmarkRecipient->email(), call_user_func("RecipientType::{$type}"));
            }
        }

        // Manually add original Message sender email as recipient because
        // the reply address is bemail's inbound address.
        $recipientEmails->addEmailToType($originalMessage->sender_email, RecipientType::standard());

        return $recipientEmails;
    }
}
