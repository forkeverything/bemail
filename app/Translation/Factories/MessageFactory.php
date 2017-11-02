<?php


namespace App\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\TranslationStatus;
use App\User;

/**
 * MessageFactory - Creates Messages (new and replies).
 *
 * @package App\Translation\Factories
 */
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
     * Recipient models.
     *
     * @var array
     */
    protected $recipients = [];

    /**
     * Create Message model.
     *
     * @return $this
     */
    protected function createNewMessage()
    {
        $this->messageModel = $this->user->messages()->create([
            'subject' => $this->formRequest->subject,
            'body' => $this->formRequest->body,
            'auto_translate_reply' => !! $this->formRequest->auto_translate_reply,
            'send_to_self' => !! $this->formRequest->send_to_self,
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
        if($this->formRequest->send_to_self) return $this;
        $emails = explode(',', $this->formRequest->recipients);
        foreach ($emails as $email) {
            $recipient = (new RecipientFactory($this->messageModel, $email))->make();
            array_push($this->recipients, $recipient->id);
        }
        $this->messageModel->recipients()->sync($this->recipients);
        return $this;
    }

    /**
     * Create Attachment(s) for this Message from Request.
     *
     * @return $this
     */
    protected function createAttachmentsFromFormRequest()
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
     * Make a new Message.
     * New in this case meaning that the Message is NOT a reply to another
     * Message.
     *
     * @param CreateMessageRequest $request
     * @param User $user
     * @return mixed
     */
    public static function makeNewMessage(CreateMessageRequest $request, User $user)
    {
        $factory = new static();
        $factory->user = $user;
        $factory->formRequest = $request;
        $factory->createNewMessage()
                ->createRecipients()
                ->createAttachmentsFromFormRequest();
        return $factory->messageModel;
    }
}