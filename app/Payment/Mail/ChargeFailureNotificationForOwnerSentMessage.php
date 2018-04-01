<?php

namespace App\Payment\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChargeFailureNotificationForOwnerMessage
 *
 * For when the owner is the one who sent the message.
 *
 * @package App\Translation\Mail
 */
class ChargeFailureNotificationForOwnerSentMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->message->subject ? "CHARGE FAILED: {$this->message->subject}" : "CHARGE FAILED";
        return $this->subject($subject)
                    ->view('emails.payment.html.charge-failure-notification-for-owner-sent-message')
                    ->text('emails.payment.html.charge-failure-notification-for-owner-sent-message');
    }
}
