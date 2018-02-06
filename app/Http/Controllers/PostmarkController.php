<?php

namespace App\Http\Controllers;

use App\Mail\Translation\Mail\MessageReplyReceivedNotification;
use App\Translation\Mail\ErrorSendingReply;
use App\Translation\Contracts\Translator;
use App\Translation\Exceptions\Handlers\TranslationExceptionHandler;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use App\Translation\Reply;
use App\Translation\Utilities\AttachmentFileBuilder;
use App\Translation\Utilities\EmailReplyParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostmarkController extends Controller
{

    /**
     * Handle inbound mail callback from Postmark.
     * @param Request $request
     * @param Translator $translator
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function postInboundMail(Request $request, Translator $translator)
    {

        // Name of person who sent email
        $fromName = $request["FromName"];
        // Address sent from
        $fromAddress = $request["From"];
        // Email Main
        $subject = $request["Subject"];
        // Only get the reply in plain-text. Already checked (manually)
        // this to be true.
        $body = $request["TextBody"];
        $strippedTextBody = EmailReplyParser::parse($body);
        // Recipients
        $recipients = $this->parseRecipients($request);
        // Attachments
        $attachments = AttachmentFileBuilder::createPostmarkAttachmentFiles($request["Attachments"]);
        // Address sent to
        $inboundAddress = $request["OriginalRecipient"];
        // Inbound Address Convention:
        // - snake_case for incoming mail address
        // - first part specifies the type of email
        // - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message
        $inboundArray = explode("_", $inboundAddress);

        // Replying to a Message?
        if ($inboundArray[0] === "reply") {
            // Grab everything until '@'
            preg_match("/.*(?=@)/", $inboundArray[1], $matches);
            // First match is the message's hash ID
            $messageHash = $matches[0];
            // Find message we're replying to
            if ($originalMessage = Message::findByHash($messageHash)) {

                // TODO ::: Create event to handle when reply received.

                // Try to make reply message and translate
                try {
                    $reply = Reply::create([
                        'sender_email' => $fromAddress,
                        'sender_name' => $fromName,
                        'original_message_id' => $originalMessage->id
                    ]);

                    $message = MessageFactory::reply($reply)
                                             ->recipientEmails($recipients)
                                             ->subject($subject)
                                             ->body($strippedTextBody)
                                             ->attachments($attachments)
                                             ->make();
                    // Try to translate message
                    try {
                        $translator->translate($message);
                    } catch (TranslationException $e) {
                        TranslationExceptionHandler::got($e)->for($message)->handle();
                        throw new \Exception;
                    }

                    // Notify reply sender that we got their reply.
                    Mail::to($message->senderEmail())->send(new MessageReplyReceivedNotification($message));

                } catch (\Exception $exception) {
                    // Some error occurred
                    // - Send notification to sender (person replying) of failure to send reply. Need new
                    Mail::to($fromAddress)->send(new ErrorSendingReply($originalMessage, $subject, $strippedTextBody));
                    // mail notification
                }
            };
        }

        return response("Received Email", 200);
    }

    /**
     * Parses recipient emails out of Postmark's POST request.
     * Store the recipient emails by the recipient types within an array. This is
     * the same format as 'recipientEmails' prop in RecipientFactory.
     * Currently BccFull always returns empty [].
     *
     * @param Request $request
     * @return array
     */
    protected function parseRecipients(Request $request)
    {

        $recipients = [
            'standard' => [],
            'cc' => [],
            'bcc' => []
        ];

        $keys = [
            'ToFull' => 'standard',
            'CcFull' => 'cc',
            'BccFull' => 'bcc'
        ];

        foreach($keys as $key => $type) {
            foreach($request[$key] as $recipientJson) {
                $email = $recipientJson["Email"];
                // skip the inbound email address or team@bemail.io (when hitting reply-all)
                $domain = explode('@', $email)[1];
                if($domain == 'in.bemail.io' || $domain == 'bemail.io') continue;
                array_push($recipients[$type], $email);
            }
        }

        return $recipients;
    }
}
