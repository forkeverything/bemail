<?php

namespace App\Http\Controllers;

use App\Translation\Mail\ErrorSendingReply;
use App\Translation\Contracts\Translator;
use App\Translation\Exceptions\Handlers\TranslationExceptionHandler;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostmarkController extends Controller
{

    /**
     * Handle inbound mail callback from Postmark.
     * @param Request $request
     * @param Translator $translator
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postInboundMail(Request $request, Translator $translator)
    {

        // Address sent from
        $fromAddress = $request["From"];
        // Email Main
        $subject = $request["Subject"];
        // Only get the reply in plain-text. Already checked (manually)
        // this to be true.
        $body = $request["StrippedTextReply"];
        // Recipients
        $recipients = $this->parseRecipients($request);

        // TODO ::: Convert attachments into an array of PostMarkAttachment classes.
        $attachments = $request["Attachments"];

        // Address sent to
        $inboundAddress = $request["OriginalRecipient"];

        // Inbound Address Convention:
        // - snake_case for incoming mail address
        // - first part specifies the type of email
        // - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message

        $inboundArray = explode("_", $inboundAddress);

        // Re-write translated message email view so that it's:
        // 1. Easily parse-able here
        // 2. Indicate that only original sender will receive translated message.

        // Replying to a Message
        if ($inboundArray[0] === "reply") {

            // Grab everything until '@'
            preg_match("/.*(?=@)/", $inboundArray[1], $matches);
            // First match is the message's hash ID
            $messageHash = $matches[0];
            // Find message we're replying to
            if ($originalMessage = Message::findByHash($messageHash)) {

                // Try to make reply and translate
                try {
                    $message = MessageFactory::makeReply($originalMessage)
                                             ->from($fromAddress)
                                             ->recipientEmails($recipients)
                                             ->subject($subject)
                                             ->body($body)
                                             ->attachments($attachments)
                                             ->make();
                    // Translate message
                    try {
                        $translator->translate($message);
                    } catch (TranslationException $e) {
                        TranslationExceptionHandler::got($e)->for($message)->handle();
                        throw new \Exception;
                    }
                } catch (\Exception $exception) {
                    // Some error occurred
                    // - Send notification to sender (person replying) of failure to send reply. Need new
                    Mail::to($fromAddress)->send(new ErrorSendingReply($originalMessage, $subject, $body));
                    // mail notification
                }

                // Success!
                // - TODO ::: Send notifications to sender
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

        \Log::info('PARSED INBOUND RECIPIENTS', [
            'recipients' => $recipients
        ]);

        return $recipients;
    }
}
