<?php

namespace App\Http\Controllers;

use App\Translation\Message;
use App\Translation\TranslationStatus;
use Illuminate\Http\Request;


class GengoController extends Controller
{


    /**
     * Pick up the callback fromm Gengo.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function postPickUp(Request $request)
    {

        // Gengo posts the response inside a 'job' parameter
        $body = json_decode($request->all()["job"], true);
        $messageHash = json_decode($body["custom_data"], true)["message_id"];
        $status = $body["status"];

        // What Message is this callback for?
        $message = Message::findByHash($messageHash);

        switch ($status) {
            // Pending: Translator has begun work.
            case "pending":
                // Update message status
                $message->updateStatus(TranslationStatus::pending());
                break;
            // Approved: job (completed translation)
            case "approved":
                // Update message status
                $message->updateStatus(TranslationStatus::approved());
                // TODO ::: Finish the rest of the callback
                // Send out actual email
                // Send notification to sender
                    // remember to eager-load recipients, sourceLanguage, targetLanguage
                break;
            default:
                break;
        }

        return response("Got it", 200);
    }
}
