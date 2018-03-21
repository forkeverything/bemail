<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Events\NewMessageRequestReceived;
use App\Contracts\Translation\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Factories\AttachmentFactory\FormUploadedFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message\NewMessageFields;
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

            $fields = new NewMessageFields($request);

            // Create models outside of event listeners because Laravel doesn't allow
            // serialization of Request or UploadedFile.

            $message = Auth::user()->newMessage()
                           ->subject($fields->subject())
                           ->body($fields->body())
                           ->autoTranslateReply($fields->autoTranslateReply())
                           ->sendToSelf($fields->sendToSelf())
                           ->langSrcId($fields->langSrcId())
                           ->langTgtId($fields->langTgtId())
                           ->make();

            $recipientEmails = RecipientEmails::new()->addListOfStandardEmails($fields->recipients());
            $message->newRecipients()
                    ->recipientEmails($recipientEmails)
                    ->make();

            $attachmentFiles = FormUploadedFile::convertArray($fields->attachments());
            $message->newAttachments()
                    ->attachmentFiles($attachmentFiles)
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
