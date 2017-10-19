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
        $body = $request->all()["job"];

        \Log::info($body);
//
//        $customData = json_decode($body["custom_data"], true);
//
//        $messageId = \Vinkla\Hashids\Facades\Hashids::decode('message', $body["custom_data"]["message_id"]);
//
//        \Log::info('FOR MESSAGE ID: ' . $messageId);

        // Pending: Translator has begun work.

        // Approved: job (completed translation)


        return response("Got it", 200);
    }
}
