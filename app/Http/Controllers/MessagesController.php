<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Contracts\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Factories\MessageFactory\RecipientEmails;
use App\Translation\RecipientType;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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

            $message = Auth::user()->newMessage()
                ->setSubject($request->subject)
                ->setBody($request->body)
                ->setAutoTranslateReply(!!$request->auto_translate_reply)
                ->setSendToSelf(!!$request->send_to_self)
                ->setLangSrcId(Language::findByCode($request->lang_src)->id)
                ->setLangTgtId(Language::findByCode($request->lang_tgt)->id)
                ->setRecipientEmails(RecipientEmails::new()->addListToType($request->recipients, RecipientType::standard()))
                ->setAttachments(FormUploadedFile::convertArray($request->attachments))
                                   ->make();

            event(new NewMessageRequestReceived($message, $translator));
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
