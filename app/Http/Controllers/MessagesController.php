<?php

namespace App\Http\Controllers;

use App\Contracts\Translation\Translator;
use App\Factory\MessageFactory;
use App\Http\Requests\CreateMessageRequest;
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

    /**
     * Send the Message.
     *
     * @param CreateMessageRequest $request
     * @param Translator $translator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postSendMessage(CreateMessageRequest $request, Translator $translator)
    {
        $factory = new MessageFactory($request, Auth::user(), $translator);
        $factory->make();   // Any errors here?
        // flash message
        // Return to compose screen
        return redirect()->back();
    }
}
