<?php

namespace App\Http\Controllers;

use App\Language;
use App\Mail\Translation\Mail\SystemTranslationError;
use App\Translation\Contracts\Translator;
use App\Translation\Factories\MessageFactory;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Mail\ReceivedRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

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
     * @throws Exception
     */
    public function postSendMessage(CreateMessageRequest $request, Translator $translator)
    {
        try {
            // Create Message and begin translation.
            $message = (new MessageFactory($request, Auth::user()))->make();
            // Attempt to translate our Message
            $translator->translate($message);
            // Send notification email (manually)
            Mail::to($message->sender)->send(new ReceivedRequest($message));
            // TODO ::: If we need to do a lot of subsequent tasks, here we should send the email
            // using an event-listener or through a notification (for multiple channels).
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
        }

        // TODO ::: Try to charge user here
            // If it fails, cancel translator job

        flash()->success('Success! Your message will be translated shortly.');
        // Return to compose screen
        return redirect()->back();
    }
}
