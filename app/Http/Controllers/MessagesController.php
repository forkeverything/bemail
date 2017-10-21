<?php

namespace App\Http\Controllers;

use App\Language;
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
        return view('messages.compose', [
                'languages' => Language::all(),
                'userLang' => $userLang
            ]
        );
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
            // Create Message and begin translation.
            (new MessageFactory($request, Auth::user(), $translator))->make();
        } catch (Exception $e) {
            if(env('APP_ENV') == 'production') {
                // Catch all exceptions, and return back flashing
                // an error message. Letting the user know
                // that their message will NOT be sent.
                flash()->error('Your message could not be sent and you have not been charged. Please try again or contact us for help.');
                return redirect()->back();
            } else {
                // In development, just throw the original exception.
                throw $e;
            }
            // TODO ::: Notify admin of failure
        }

        // TODO ::: Try to charge user here
            // If it fails, cancel translator job

        flash()->success('Success! Your message will be translated shortly.');
        // Return to compose screen
        return redirect()->back();
    }
}
