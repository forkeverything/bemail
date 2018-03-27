<?php

namespace App\Http\Controllers;

use App\Contracts\InboundMail\InboundMailRecipient;
use App\Contracts\InboundMail\InboundMailRequest;
use App\InboundMail\Postmark\CanHandleReplies;
use App\InboundMail\Postmark\PostmarkInboundMailRequest;
use App\InboundMail\Postmark\Reportable;
use App\Traits\LogsExceptions;
use App\Translation\Events\FailedCreatingReply;
use App\Translation\Events\ReplyMessageCreated;
use App\Contracts\Translation\Translator;
use App\Translation\Factories\AttachmentFactory\PostmarkAttachmentFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Mail\OriginalMessageNotFoundNotification;
use App\Translation\Message;
use App\Translation\Message\ReplyMessageBuilder;
use App\Translation\Recipient\RecipientType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * PostmarkController
 *
 * Handles HTTP call-backs from Postmark.
 *
 * @package App\Http\Controllers
 */
class PostmarkController extends Controller
{

    use CanHandleReplies, LogsExceptions;

    /**
     * Handle inbound mail callback from Postmark.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postInboundMail(Request $request)
    {

        try {

            $postmarkRequest = new PostmarkInboundMailRequest($request);

            switch ($postmarkRequest->action()) {

                case 'reply':

                    $this->handleReply($postmarkRequest);
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

