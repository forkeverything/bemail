<?php


namespace App\Translation\Message;


use App\Translation\Message;

abstract class MessageBuilder
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * Get the built Message.
     *
     * @return Message
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @throws \Exception
     */
    protected function checkForMessageBeforeBuildingRecipients()
    {
        if (is_null($this->message)) {
            throw new \Exception("Must build Message model before Recipient(s).");
        }
    }

    /**
     * @throws \Exception
     */
    protected function checkForMessageBeforeBuildingAttachments()
    {
        if (is_null($this->message)) {
            throw new \Exception("Must build Message model before Attachment(s).");
        }
    }

}