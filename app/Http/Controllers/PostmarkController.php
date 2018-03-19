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
                if (!$originalMessage = Message::findByHash($postmarkRequest->target())) {
                    break;
                }
                event(new ReplyReceived($postmarkRequest, $originalMessage, $translator));
                break;
            default:
                break;
        }

        return response("Received Email", 200);
    }


}

