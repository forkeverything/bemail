<?php


namespace App\Factory;

use App\Contracts\Translation\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\TranslationStatus;
use App\User;

class MessageFactory
{

    /**
     * The User writing the Message.
     *
     * @var User
     */
    protected $user;

    /**
     * Compose Message form request.
     *
     * @var
     */
    protected $formRequest;

    /**
     * Newly created Message model.
     *
     * @var
     */
    protected $messageModel;


    protected $translator;

    public function __construct(CreateMessageRequest $createMessageRequest, User $user, Translator $translator)
    {
        $this->user = $user;
        $this->formRequest = $createMessageRequest;
        $this->translator = $translator;
    }

    /**
     * Create Message model.
     *
     * @return $this
     */
    protected function createMessage()
    {
        $this->messageModel = $this->user->messages()->create([
            'subject' => $this->formRequest->subject,
            'body' => $this->formRequest->body,
            'lang_src_id' => Language::findByCode($this->formRequest->lang_src)->id,
            'lang_tgt_id' => Language::findByCode($this->formRequest->lang_tgt)->id,
            'translation_status_id' => TranslationStatus::pending()->id
        ]);

        return $this;
    }

    protected function assignRecipients()
    {
        // Create recipients (if they don't exist)
        // Attach to message model and user

        return $this;
    }

    protected function startTranslation()
    {
        return $this;
    }

    protected function sendNotifications()
    {
        // Send email notifications / Fire events that send emails
        return $this;
    }

    /**
     * Make a new Message.
     * This is the main method that kick-starts the whole sending a Message.
     * Messages should only ever be created using this function and no
     * where else in the app.
     *
     * @param CreateMessageRequest $createMessageRequest
     * @param User $user
     */
    static function make(CreateMessageRequest $createMessageRequest, User $user)
    {
        $factory = new static($createMessageRequest, $user);



        $factory->createMessage()
                ->assignRecipients()
                ->startTranslation()
                ->sendNotifications();
    }
}