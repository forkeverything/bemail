<?php


namespace App\Translation\Message;


use App\Translation\Factories\AttachmentFactory\FormUploadedFile;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use Illuminate\Support\Facades\Auth;

/**
 * Class NewMessageBuilder
 *
 * Builds all the models needed for a new Message.
 *
 * @package App\Translation\Message
 */
class NewMessageBuilder extends MessageBuilder
{
    /**
     * @var NewMessageFields
     */
    private $fields;

    /**
     * Create NewMessageBuilder instance.
     *
     * @param NewMessageFields $fields
     */
    public function __construct(NewMessageFields $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Build Message model.
     *
     * @return $this
     */
    public function buildMessage()
    {

        $this->message = Auth::user()->newMessage()
                             ->subject($this->fields->subject())
                             ->body($this->fields->body())
                             ->autoTranslateReply($this->fields->autoTranslateReply())
                             ->sendToSelf($this->fields->sendToSelf())
                             ->langSrcId($this->fields->langSrcId())
                             ->langTgtId($this->fields->langTgtId())
                             ->make();

        return $this;
    }

    /**
     * Build Recipient(s).
     *
     * @return $this
     * @throws \Exception
     */
    public function buildRecipients()
    {

        $this->checkForMessageBeforeBuildingRecipients();

        $recipientEmails = RecipientEmails::new()->addListOfStandardEmails($this->fields->recipients());
        $this->message->newRecipients()
                      ->recipientEmails($recipientEmails)
                      ->make();
        return $this;
    }

    /**
     * Build Attachment(s).
     *
     * @return $this
     * @throws \Exception
     */
    public function buildAttachments()
    {

        $this->checkForMessageBeforeBuildingAttachments();

        $attachmentFiles = FormUploadedFile::convertArray($this->fields->attachments());
        $this->message->newAttachments()
                      ->attachmentFiles($attachmentFiles)
                      ->make();
        return $this;
    }

}