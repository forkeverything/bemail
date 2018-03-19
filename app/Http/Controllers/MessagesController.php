<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Contracts\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
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
                           ->subject($request->subject)
                           ->body($request->body)
                           ->autoTranslateReply(!!$request->auto_translate_reply)
                           ->sendToSelf(!!$request->send_to_self)
                           ->langSrcId(Language::findByCode($request->lang_src)->id)
                           ->langTgtId(Language::findByCode($request->lang_tgt)->id)
                           ->attachments(FormUploadedFile::convertArray($request->attachments))
                           ->make();

            // Create Message Recipient(s).

            $recipientEmails = RecipientEmails::new()->addListOfStandardEmails($request->recipients);
            $message->newRecipients()
                    ->recipientEmails($recipientEmails)
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
