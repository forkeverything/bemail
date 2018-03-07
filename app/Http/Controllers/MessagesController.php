<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Contracts\Translator;
use App\Translation\Factories\MessageFactory;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Mail\ReceivedNewMessageRequest;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
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
            event(new NewMessageRequestReceived(
                $request->subject,
                $request->body,
                !!$request->auto_translate_reply,
                !!$request->send_to_self,
                Language::findByCode($request->lang_src)->id,
                Language::findByCode($request->lang_tgt)->id,
                explode(',', $request->recipients),
                $request->attachments ?: [],
                Auth::user(),
                $translator
            ));
            // TODO ::: Charge User
            // Implement another event listener that tries to charge user. If charging
            // fails, need to cancel translation job.
        } catch (Exception $e) {
            if (App::environment('production')) {
                flash()->error('System Error - Your message was not sent and you have not been charged. Please try again or contact us for help.');
                return redirect()->back();
            } else {
                throw $e;
            }
        }

        flash()->success('Success! Your message will be translated shortly.');

        // Return to compose screen
        return redirect()->back();
    }
}
