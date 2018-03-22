<?php

namespace App\Http\Controllers;

use App\Translation\Events\MessageTranslated;
use App\Translation\Translators\Gengo\GengoCallbackRequest;
use App\Translation\Message;
use App\Translation\Order\OrderStatus;
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

        // In case returning early...
        $response = response("Got it.", 200);

        try {

            $gengoRequest = new GengoCallbackRequest($request);

            // Only want job related callbacks currently (not comments).
            if(! $gengoRequest->isJobRequest()) return $response;

            /**
             * The Message this callback was for.
             *
             * @var Message $message
             */
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

        } catch (\Exception $e) {

            \Log::error('GENGO CALLBACK EXCEPTION', [
                'message' => $e->getMessage(),
                'exception' => $e
            ]);
        }

        // Much success!
        return $response;
    }
}
