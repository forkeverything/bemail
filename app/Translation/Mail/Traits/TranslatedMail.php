<?php


namespace App\Translation\Mail\Traits;


use App\Translation\Message;

trait TranslatedMail
{
    /**
     * Sets the subject of the message.
     *
     * @return $this
     */
    protected function setSubject()
    {
        $subject = $this->translatedMessage->subject ? "Translated: {$this->translatedMessage->subject}" : "Translated Message";
        $this->subject($subject);
        return $this;
    }

    /**
     * Include Attachment(s).
     * These were included when the Message was
     * originally composed.
     *
     * @return $this
     */
    protected function includeAttachments()
    {
        foreach ($this->translatedMessage->attachments as $attachment) {
            $this->attach($attachment->path, [
                'as' => $attachment->original_file_name
            ]);
        }
        return $this;
    }
}