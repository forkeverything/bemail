<?php

namespace App\Http\Controllers;

use App\Translation\Events\MessageTranslated;
use App\Translation\GengoCallbackRequest;
use App\Translation\Message;
use App\Translation\OrderStatus;
use Illuminate\Http\Request;


class GengoController extends Controller
{
    /**
     * Pick up the callback from Gengo.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function postCallback(Request $request)
    {

        $gengoRequest = new GengoCallbackRequest($request);

        // Need response as might return early. Without
        // a 200, Gengo will keep trying.
        $response = response("Got it", 200);

        // Only want job related callbacks currently (not comments).
        if(! $gengoRequest->isJobRequest()) return $response;

        // The Message this callback was for.
        $message = Message::findByHash($gengoRequest->messageHash());

        switch ($gengoRequest->status()) {
            // Pending: Translator has begun work.
            case "pending":
                $message->order->updateStatus(OrderStatus::pending());
                break;
            // Approved: Completed translation job.
            case "approved":
                event(new MessageTranslated($message, $gengoRequest->translatedBody()));
                break;
            default:
                break;
        }

        // Much success!
        return $response;
    }
}
