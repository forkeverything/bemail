<?php


namespace App\Translation\Factories;

use App\Translation\Contracts\Translator;
use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\TranslationStatus;
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

    /**
     * Translator that handles ALL translating behavior.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Recipient models.
     *
     * @var array
     */
    protected $recipients = [];

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
            'translation_status_id' => TranslationStatus::available()->id
        ]);

        return $this;
    }

    /**
     * Finds or creates Recipient(s).
     *
     * @return $this
     */
    protected function createRecipients()
    {
        $emails = explode(',', $this->formRequest->recipients);
        foreach ($emails as $email) {
            $recipient = (new RecipientFactory($this->messageModel, $email))->make();
            array_push($this->recipients, $recipient->id);
        }
        $this->messageModel->recipients()->sync($this->recipients);
        return $this;
    }

    /**
     * Create Attachment(s) for this Message.
     *
     * @return $this
     */
    protected function createAttachments()
    {
        $attachments = $this->formRequest->attachments;
        if($attachments) {
            foreach ($attachments as $uploadedFile) {
                (new AttachmentFactory($this->messageModel, $uploadedFile))->make();
            }
        }
        return $this;
    }

    /**
     * Initiate translating.
     *
     * @return $this
     */
    protected function startTranslation()
    {
        $this->translator->translate($this->messageModel);
        return $this;
    }

    protected function sendNotifications()
    {
        // TODO ::: Send email notifications / Fire events that send emails
        return $this;
    }

    /**
     * Make a new Message.
     *
     * This is the main method that kick-starts the whole sending a Message.
     * Messages should only ever be created using this function and no
     * where else in the app.
     */
    public function make()
    {
        $this->createMessage()
             ->createRecipients()
             ->createAttachments()
             ->startTranslation()
             ->sendNotifications();

        return $this->messageModel;
    }
}