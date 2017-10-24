<?php

namespace App\Http\Controllers;

use App\Mail\Translation\Mail\MessageSent;
use App\Mail\Translation\Mail\RecipientTranslatedMessage;
use App\Mail\Translation\Mail\SenderTranslatedMessage;
use App\Translation\Message;
use App\Translation\TranslationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


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

        $response = response("Got it", 200);

        // Ignore if call-back not for job (ie. comments are ignored at the moment)
        if(! array_key_exists("job", $request->all())) return $response;

        // Gengo posts the response inside a 'job' parameter
        $body = json_decode($request->all()["job"], true);
        // Parse out our message identifier that we originally sent over
        $messageHash = json_decode($body["custom_data"], true)["message_id"];
        // Get the status
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
                $message->update(['translated_body' => $body["body_tgt"]]);
                // Update message status
                $message->updateStatus(TranslationStatus::approved());
                // TODO ::: Finish the rest of the callback
                if($message->send_to_self) {
                    // Send translated message back to sender
                    Mail::to($message->sender)->send(new SenderTranslatedMessage($message));
                } else {
                    // Send translated Message to Recipient(s)
                    foreach ($message->recipients as $recipient) {
                        Mail::to($recipient->email)->send(new RecipientTranslatedMessage($message));
                    }
                    // Send translation complete notification to sender
                    Mail::to($message->sender)->send(new MessageSent($message));
                }
                break;
            default:
                break;
        }

        return $response;
    }
}
