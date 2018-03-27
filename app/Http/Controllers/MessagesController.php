<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Events\NewMessageCreated;
use App\Contracts\Translation\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Translation\Exceptions\FailedCreatingNewMessageException;
use App\Translation\Factories\AttachmentFactory\FormUploadedFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message\NewMessageBuilder;
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
     * @return \Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function postSendMessage(CreateMessageRequest $request)
    {
        try {
            // Create models outside of event listeners because Laravel doesn't allow
            // serialization of Request or UploadedFile.
            $fields = new NewMessageFields($request);
            $builder = new NewMessageBuilder($fields);
            $message = $builder->buildMessage()
                               ->buildRecipients()
                               ->buildAttachments()
                               ->message();
        } catch(\Exception $e) {
            if (App::environment('production')) {
                throw new FailedCreatingNewMessageException($e->getMessage());
            } else {
                throw $e;
            }
        }

        event(new NewMessageCreated($message));
        flash()->success('Success! Your message will be translated shortly.');
        // Return to compose screen
        return redirect()->back();
    }

}
