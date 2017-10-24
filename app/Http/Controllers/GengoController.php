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

        \Log::info(json_decode($request->all()["job"], true));

        // Gengo posts the response inside a 'job' parameter
        $body = json_decode($request->all()["job"], true);
        $messageHash = json_decode($body["custom_data"], true)["message_id"];
        // Get the status according to Gengo's API
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

                // Store Translated Body

                // Update message status
                $message->updateStatus(TranslationStatus::approved());

                // TODO ::: Finish the rest of the callback

                if($message->send_to_self) {
                    // Send translated message back to sender
                } else {
                    // Send to recipient(s)

                }

                // If sending to recipient
                    // Send out actual email
                        // If auto-translate-reply is off
                            // from address should be sender email
                        // else
                            // use message hash to make a from address to reply to
                            // to have it automatically translated
                    // Send notification to sender
                        // remember to eager-load recipients, sourceLanguage, targetLanguage
                // Else sending to sender (self)
                    // Send translated message

                break;
            default:
                break;
        }

        return response("Got it", 200);
    }
}
