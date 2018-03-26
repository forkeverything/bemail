<?php

namespace App\Payment\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChargeFailureNotificationForReplyOwner
 *
 * Let the reply message's owner know that a reply could not
 * be sent because of failure to charge his account.
 *
 * @package App\Translation\Mail
 */
class ChargeFailureNotificationForReplyOwner extends Mailable
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
        return $this->subject("Couldn't Translate Reply (Charge Failed)")
                    ->view('emails.payment.html.charge-failure-notification-for-reply-owner')
                    ->text('emails.payment.html.charge-failure-notification-for-reply-owner');
    }
}
