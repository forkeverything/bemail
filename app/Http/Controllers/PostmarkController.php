<?php

namespace App\Http\Controllers;

use App\Contracts\InboundMail\InboundMailRecipient;
use App\Contracts\InboundMail\InboundMailRequest;
use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use App\Translation\Events\ReplyReceived;
use App\Contracts\Translation\Translator;
use App\Translation\Factories\AttachmentFactory\PostmarkAttachmentFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\Message\ReplyMessageBuilder;
use App\Translation\Recipient\RecipientType;
use Illuminate\Http\Request;

/**
 * PostmarkController
 *
 * Handles HTTP call-backs from Postmark.
 *
 * @package App\Http\Controllers
 */
class PostmarkController extends Controller
{

    /**
     * Handle inbound mail callback from Postmark.
     *
     * @param PostmarkInboundMailRequest $request
     * @param Translator $translator
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postInboundMail(Request $request, Translator $translator)
    {
        try {
            $postmarkRequest = new PostmarkInboundMailRequest($request);
            switch ($postmarkRequest->action()) {
                case 'reply':

                    /**
                     * The Message being replied to.
                     *
                     * @var Message $originalMessage
                     */
                    if (!$originalMessage = Message::findByHash($postmarkRequest->target())) {
                        break;
                    }

                    // Purposely create models here (out of event listener) to
                    // avoid serialization of closure error.
                    $builder = new ReplyMessageBuilder($postmarkRequest, $originalMessage);
                    $message = $builder->buildMessage()
                                       ->buildRecipients()
                                       ->buildAttachments()
                                       ->message();

                    event(new ReplyReceived($message, $translator));
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {
            // Log error here but don't re-throw. Must return 200, otherwise
            // Postmark will keep trying.
            \Log::error('POSTMARK INBOUND MAIL EXCEPTION', [
                'message' => $e->getMessage(),
                'exception' => $e
            ]);
        }
        return response("Received Email", 200);
    }

}

