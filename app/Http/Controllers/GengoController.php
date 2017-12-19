<?php

namespace App\Http\Controllers;

use App\Translation\Events\MessageTranslated;
use App\Translation\Message;
use App\Translation\TranslationStatus;
use Illuminate\Http\Request;


class GengoController extends Controller
{
    /**
     * Pick up the callback from Gengo.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postCallback(Request $request)
    {
        // Store our response, in case we need to return early. If
        // we don't return a 200 - Gengo will keep trying.
        $response = response("Got it", 200);
        // Ignore if call-back not for job (ie. comments are ignored at the moment)
        if(! array_key_exists("job", $request->all())) return $response;
        // Gengo posts the response inside a 'job' parameter
        $body = json_decode($request->all()["job"], true);
        // Parse out our message identifier that we originally sent over
        $messageHash = json_decode($body["custom_data"], true)["message_id"];
        // Get the status
        $status = $body["status"];
        // Which Message is this callback for?
        $message = Message::findByHash($messageHash);
        // Switch on status - what was the callback for?
        switch ($status) {
            // Pending: Translator has begun work.
            case "pending":
                // Update message status
                $message->updateStatus(TranslationStatus::pending());
                break;
            // Approved: Job (completed translation)
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
