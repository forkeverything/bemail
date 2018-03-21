<?php

namespace App\Http\Controllers;

use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use App\Translation\Events\ReplyReceived;
use App\Contracts\Translation\Translator;
use App\Translation\Message;
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
     * @throws \Exception
     */
    public function postInboundMail(Request $request, Translator $translator)
    {
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
                event(new ReplyReceived($postmarkRequest, $originalMessage, $translator));
                break;
            default:
                break;
        }

        return response("Received Email", 200);
    }


}

