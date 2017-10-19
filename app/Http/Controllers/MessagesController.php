<?php

namespace App\Http\Controllers;

use App\Translation\Contracts\Translator;
use App\Translation\Factories\MessageFactory;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Exceptions\TranslationException;
use Exception;
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
     * @throws TranslationException
     */
    public function postSendMessage(CreateMessageRequest $request, Translator $translator)
    {

        try {
            (new MessageFactory($request, Auth::user(), $translator))->make();
        } catch (Exception $e) {
            if(env('APP_ENV') == 'production') {
                // Catch any and all exceptions to indicate
                // complete failure and the message will
                // NOT be translated.
                throw new TranslationException;
            } else {
                // In development, just throw the original exception.
                throw $e;
            }
        }

        flash()->success('Success! Your message will be translated shortly.');
        // Return to compose screen
        return redirect()->back();
    }
}
