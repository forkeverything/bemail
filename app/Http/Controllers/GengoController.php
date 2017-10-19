<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GengoController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Pick up the callback fromm Gengo.
     *
     * @param Request $request
     */
    public function pickUp(Request $request)
    {
        \Log::info("url {$request->url()}");
        \Log::info("secure {$request->secure()}");
        \Log::info("ip {$request->ip()}");
        \Log::info(print_r($request->all(), true));
        return response("Got it", 200);
    }
}
