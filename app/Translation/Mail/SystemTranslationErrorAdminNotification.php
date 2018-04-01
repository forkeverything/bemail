<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Tells System/Admins of translation error.
 * This mail is  sent when the error is caused by the system.
 * Skip mark-down formatting.
 *
 * Class SystemTranslationErrorAdminNotification
 * @package App\Translation\Mail
 */
class SystemTranslationErrorAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $messageWithError;

    /**
     * Create a new message instance.
     *
     * @param Message $messageWithError
     */
    public function __construct(Message $messageWithError)
    {
        $this->messageWithError = $messageWithError->load([
            'error',
            'user'
        ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("SYSTEM ERROR: TRANSLATION FAILED")
            ->view('emails.system.html.translation-error-admin-notification');

    }
}
