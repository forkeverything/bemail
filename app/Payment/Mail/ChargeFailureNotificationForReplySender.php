<?php

namespace App\Payment\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChargeFailureNotificationForReplySender
 *
 * Let the reply sender know that their reply was not sent
 * because the owner could not be charged for the fees.
 *
 * @package App\Translation\Mail
 */
class ChargeFailureNotificationForReplySender extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    /**
     * @var Collection
     */
    public $threadMessages;

    /**
     * Create a new message instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->threadMessages = $message->thread()->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reply Not Translated")
            ->view('emails.payment.html.charge-failure-notification-for-reply-sender')
            ->text('emails.payment.text.charge-failure-notification-for-reply-sender');
    }
}
