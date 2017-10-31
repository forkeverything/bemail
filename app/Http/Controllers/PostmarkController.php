<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostmarkController extends Controller
{
    public function postIncoming(Request $request)
    {

        $inboundEmailAddress = $request["OriginalRecipient"];

        // Inbound Address Convention:
        // - snake_case for incoming mail address
        // - first part specifies the type of email
        // - ie. reply_s0m3h4$h@in.bemail.io, for replies to a specific Message

        $inboundArray = explode("_", $inboundEmailAddress);

        // Replying to comment
        if($inboundArray[0] === "reply") {
            // Grab everything until '@'
            preg_match("/.*(?=@)/", $inboundArray[1], $matches);
            // Re-write translated message email view so that it's easily parse-able here
            // Find which message we're replying to
            // Create a way of differentiating between:
                // 1. Message sent by User
                // 2. Message created as a reply from a sender that might not be registered.
            // Create attachments
            // Handle attachments
            // Translate message?
            // Send notification to sender (person replying) and receiver (original sender)
        }

        return response("Received Email", 200);
    }
}
