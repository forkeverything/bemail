<?php

namespace App\Http\Controllers;

use App\Contracts\InboundMail\ReplyHandler;
use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use App\Traits\LogsExceptions;
use Exception;
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

    use LogsExceptions;

    /**
     * Handle inbound mail callback from Postmark.
     *
     * @param Request $request
     * @param ReplyHandler $replyHandler
     * @return \Illuminate\Http\Response
     */
    public function postInboundMail(Request $request, ReplyHandler $replyHandler)
    {

        try {
            $postmarkRequest = new PostmarkInboundMailRequest($request);
            switch ($postmarkRequest->action()) {
                case 'reply':
                    $replyHandler->handleRequest($postmarkRequest);
                    break;
                default:
                    break;
            }
        } catch (Exception $e) {
            $this->logException('FAILED_HANDLING_INBOUND_POSTMARK_MAIL', $e);
        }

        // Need to return 200 or Postmark will keep trying.
        return response("Received Email", 200);

    }

}

