<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Compose a new message.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCompose()
    {
        $userLang = Auth::user()->defaultLanguage;
        return view('messages.compose', ['userLang' => $userLang]);
    }

    public function postSendMessage(Request $request)
    {
        dd($request->all());
    }
}
