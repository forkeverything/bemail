<?php

namespace App\Http\Controllers;

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
        \Log::info($body);
        \Log::info("custom data: " . $body["custom_data"]);

        $messageHash = json_decode($body["custom_data"], true)["message_id"];

        \Log::info("message hash: " . $messageHash);

        $status = $body["status"];

        \Log::info("status: " . $status);

//        $messageId = \Vinkla\Hashids\Facades\Hashids::decode('message', $body["custom_data"]["message_id"]);
//
//        \Log::info('FOR MESSAGE ID: ' . $messageId);



        // Pending: Translator has begun work.

        // Approved: job (completed translation)


        return response("Got it", 200);
    }
}
