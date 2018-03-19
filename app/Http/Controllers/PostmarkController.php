<?php

namespace App\Http\Controllers;

use App\Translation\Attachments\PostmarkAttachmentFile;
use App\Translation\Events\ReplyReceived;
use App\Translation\Contracts\Translator;
use App\Translation\Factories\MessageFactory;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\PostmarkInboundMailRequest;
use App\Translation\PostmarkInboundRecipient;
use App\Translation\RecipientType;
use App\Translation\Utilities\EmailReplyParser;
use Illuminate\Http\Request;

/**
 * PostmarkController
 * Handles HTTP call-backs from Postmark.
 *
 * @package App\Http\Controllers
 */
class PostmarkController extends Controller
{

    /**
     * Handle inbound mail callback from Postmark.
     *
     * @param Request $request
     * @param Translator $translator
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function postInboundMail(Request $request, Translator $translator)
    {

        $postmarkRequest = new PostmarkInboundMailRequest($request);

        switch ($postmarkRequest->action()) {
            case 'reply':
                // Find message the reply is intended for.
                if ($originalMessage = Message::findByHash($postmarkRequest->target())) {

                    /**
                     * @var Message $originalMessage
                     */
                    $message = $originalMessage->newReply()
                                               ->senderEmail($postmarkRequest->fromAddress())
                                               ->senderName($postmarkRequest->fromName())
                                               ->subject($postmarkRequest->subject())
                                               ->body($postmarkRequest->strippedTextBody())
                                               ->make();

                    // Create Recipient(s).
                    $message->newRecipients()
                            ->recipientEmails($this->recipientEmails($postmarkRequest, $originalMessage))
                            ->make();

                    // Create Attachment(s).
                    $message->newAttachments()
                            ->attachmentFiles(PostmarkAttachmentFile::convertArray($postmarkRequest->attachments()))
                            ->make();

                    event(new ReplyReceived($message, $translator));
                };
                break;
            default:
                break;
        }

        return response("Received Email", 200);
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

        /**
         * @var $postmarkRecipient PostmarkInboundRecipient
         */
        foreach ($types as $type) {
            $recipients = call_user_func([$postmarkRequest, "{$type}Recipients"]);
            foreach ($recipients as $postmarkRecipient) {
                $recipientEmails->addEmailToType($postmarkRecipient->email(), call_user_func("RecipientType::{$type}"));
            }
        }

        // Manually add original Message sender email as recipient because
        // the reply address is bemail's inbound address.
        $recipientEmails->addEmailToType($originalMessage->sender_email, RecipientType::standard());

        return $recipientEmails;
    }
}

