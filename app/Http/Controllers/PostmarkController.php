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
            \Log::info($request[$key]);
            break;
//            foreach($request[$key] as $json) {
//                $recipient = json_decode($json, true);
//                array_push($recipients[$type], $recipient["Email"]);
//            }
        }
    }

    public function postIncoming(Request $request, Translator $translator)
    {

        // Address sent from
        $fromAddress = $request["From"];
        // Email Main
        $subject = $request["Subject"];
        // Only get the reply in plain-text. Already checked (manually)
        // this to be true.
        $body = $request["StrippedTextReply"];

        $recipients = $this->parseRecipients($request);

        \Log::info('success!', ['recipients' => $recipients]);
        return "parsed recipients";

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

                    // TODO ::: set recipient emails

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
}
