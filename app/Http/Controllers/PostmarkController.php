<?php

namespace App\Http\Controllers;

use App\Translation\Attachments\PostmarkAttachmentFile;
use App\Translation\Events\ReplyReceived;
use App\Translation\Contracts\Translator;
use App\Translation\Factories\MessageFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\RecipientType;
use App\Translation\Reply;
use App\Translation\Utilities\AttachmentFileBuilder;
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
        $fromName = $request["FromName"];
        $fromAddress = $request["From"];
        $subject = $request["Subject"];
        $strippedTextBody = EmailReplyParser::parse($request["TextBody"]);
        $attachments = PostmarkAttachmentFile::convertArray($request["Attachments"]);
        $action = $this->action($request["OriginalRecipient"]);
        $target = $this->target($request["OriginalRecipient"]);

        switch ($action) {
            case 'reply':
                // Find message the reply is intended for.
                if ($originalMessage = Message::findByHash($target)) {

                    $recipients = $this->recipientEmails($request, $originalMessage);

                    $message = $originalMessage->newReply()
                                               ->setSenderEmail($fromAddress)
                                               ->setSenderName($fromName)
                                               ->setRecipientEmails($recipients)
                                               ->setSubject($subject)
                                               ->setBody($strippedTextBody)
                                               ->setAttachments($attachments)
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
     * The action that this inbound email is doing.
     *
     * @param $inboundAddress
     * @return mixed
     */
    protected function action($inboundAddress)
    {
        return $this->inboundAddressArray($inboundAddress)[0];
    }

    /**
     * The target that this email is intended for.
     *
     * @param $inboundAddress
     * @return mixed
     */
    protected function target($inboundAddress)
    {
        preg_match("/.*(?=@)/", $this->inboundAddressArray($inboundAddress)[1], $matches);
        return $matches[0];
    }

    /**
     * Turns the inbound address into an array.
     *
     * Inbound Address Convention:
     * - snake_case for incoming mail address
     * - first part specifies the type of email
     * - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message
     *
     * @param $inboundAddress
     * @return array
     */
    protected function inboundAddressArray($inboundAddress)
    {
        return explode("_", $inboundAddress);
    }

    /**
     * Creates RecipientEmails from a Postmark inbound email POST request.
     *
     * @param Request $request
     * @param Message $originalMessage
     * @return RecipientEmails
     */
    protected function recipientEmails(Request $request, Message $originalMessage)
    {

        $recipientEmails = RecipientEmails::new();

        $keys = [
            'ToFull' => RecipientType::standard(),
            'CcFull' => RecipientType::cc(),
            'BccFull' => RecipientType::bcc()
        ];

        foreach ($keys as $key => $type) {
            foreach ($request[$key] as $recipientJson) {
                $recipientEmails->addEmailToType($recipientJson["Email"], $type);
            }
        }

        // Manually add original Message sender email as recipient because
        // the reply address is bemail's inbound address.
        $recipientEmails->addEmailToType($originalMessage->sender_email, RecipientType::standard());

        return $recipientEmails;
    }
}

