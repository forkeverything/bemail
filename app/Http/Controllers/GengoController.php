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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function pickUp(Request $request)
    {
        \Log::info(print_r($request->all(), true));
        return response("Got it", 200);
    }
}
