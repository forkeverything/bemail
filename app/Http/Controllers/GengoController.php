<?php

namespace App\Http\Controllers;

use App\Translation\Events\MessageTranslated;
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
        // Store our response, in case we need to return early. If
        // we don't return a 200 - Gengo will keep trying.
        $response = response("Got it", 200);
        // Ignore if call-back not for job (ie. comments are ignored at the moment)
        if(! array_key_exists("job", $request->all())) return $response;
        // Gengo posts the response inside a 'job' key
        $body = json_decode($request->all()["job"], true);
        // The message identifier that was sent with translation job.
        $messageHash = json_decode($body["custom_data"], true)["message_hash"];
        // Message status
        $status = $body["status"];
        // The Message this callback was for.
        $message = Message::findByHash($messageHash);

        switch ($status) {
            // Pending: Translator has begun work.
            case "pending":
                $message->order->updateStatus(OrderStatus::pending());
                break;
            // Approved: Completed translation job.
            case "approved":
                event(new MessageTranslated($message, $body["body_tgt"]));
                break;
            default:
                break;
        }
        // Much success!
        return $response;
    }
}
