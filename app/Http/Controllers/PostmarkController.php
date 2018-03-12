<?php

namespace App\Http\Controllers;

use App\Translation\Events\ReplyReceived;
use App\Translation\Contracts\Translator;
use App\Translation\Message;
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
        $recipients = $this->parseRecipients($request);
        $attachments = AttachmentFileBuilder::createPostmarkAttachmentFiles($request["Attachments"]);
        $action = $this->action($request["OriginalRecipient"]);
        $target = $this->target($request["OriginalRecipient"]);

        switch ($action) {
            case 'reply':
                // Find message the reply is intended for.
                if ($originalMessage = Message::findByHash($target)) {
                    event(new ReplyReceived($fromAddress, $fromName, $originalMessage, $recipients, $subject, $strippedTextBody, $attachments, $translator));
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
